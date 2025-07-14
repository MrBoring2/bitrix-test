<?php

use Bitrix\Main\Loader;
use Bitrix\Sale\Fuser;
use Bitrix\Main\Application;
use Bitrix\Catalog\PriceTable;

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

Loader::includeModule('sale');
Loader::includeModule('catalog');


class CartAjaxHandler{
    public function handeRequest() {
        $request = Application::getInstance()->getContext()->getRequest();
        $action = $request->getPost('action');

        switch($action){
            case 'add' :
                $this->AddToCart($request);
                break;
            case 'remove':
                $this->RemoveFromCart($request);
                break;
            case 'update':
                $this->UpdateCart($request);
                break;
            case 'get':
                $this->GetCart();
                break;
            default:
                $this->SendResponse(['error' => 'Неизвествное действие']);
                break;
        }
    }

    protected function AddToCart($request) {
        $id = (int)$request->getPost('id');
        $quantity = (int)$request->getPost('quantity');
        $props = $request->getPost('props') ?? [];

        if(!$id || $quantity < 0) {
            $this->SendResponse(['error'=> 'Некорректные параметры']);
            return;
        }

        $priceRow = PriceTable::getList([
            'select' => ['ID', 'PRICE', 'CURRENCY'],
            'filter' => [
                '=PRODUCT_ID' => $id,
                '=CATALOG_GROUP_ID' => 1,
            ],
        ])->fetch();

        if (!$priceRow) {
            $this->sendResponse(['error' => 'Цена не найдена']);
            return;
        }

        $select = ['ID', 'IBLOCK_ID'];
        foreach ($props as $code) {
            if ($code === 'NAME' || $code === 'DETAIL_PICTURE') {
                $select[] = $code;
            } else {
                $select[] = 'PROPERTY_' . strtoupper($code);
            }
        }

        $res = CIBlockElement::GetList([], ['ID' => $id], false, false, $select);
        if(!$element = $res->GetNext()){
            $this->SendResponse(['error'=> 'Товар не найден']);
            return;
        }

        $resultProps = [];
        foreach ($props as $code) {
            $upperCode = strtoupper($code);

            if ($code === 'NAME') {
                $value = $element['NAME'] ?? null;
            } elseif ($code === 'DETAIL_PICTURE') {
                $pictureId = $element['DETAIL_PICTURE'] ?? null;
                if ($pictureId) {
                    $file = \CFile::GetFileArray($pictureId);
                    $value = $file['SRC'] ?? null;
                }
            } else {
                $value = $element['PROPERTY_' . $upperCode . '_VALUE'] ?? null;
            }

            if ($value !== null) {
                $resultProps[] = [
                    'NAME' => $code,
                    'CODE' => $upperCode,
                    'VALUE' => $value,
                    'SORT' => 100
                ];
            }
        }

        $arFields = [
            'PRODUCT_ID' => $id,
            'PRODUCT_PRICE_ID' => $priceRow['ID'],
            'QUANTITY' => $quantity,
            'PRICE' => $priceRow['PRICE'],
            'CURRENCY' => $priceRow['CURRENCY'],
            'LID' => SITE_ID,
            'DELAY' => 'N',
            'CAN_BUY' => 'Y',
            'MODULE' => 'catalog',
            'NAME' => 'Товар №' . $id,
            'PROPS' => $resultProps
        ];

        $basketId = CSaleBasket::Add($arFields);

        if($basketId) {
            $this->SendResponse(['success'=> true, 'basket_id' => $basketId]);
        } else {
            $this->SendResponse(['error' => 'Ошибка при добавлении']);
        }
    }

    protected function RemoveFromCart($request) {
        $positionId = (int)$request->getPost('positionId');
        if(!$positionId){
            $this->SendResponse(['error'=> 'Неккоректный ID корзины']);
        }

        if(CSaleBasket::Delete($positionId)){
            $this->SendResponse(['success'=> true]);
        }
        else {
            $this->SendResponse(['error'=>'Не удалось удалить товар из корзины']);
        }
    }

    protected function UpdateCart($request){
        $productId = (int)$request->getPost('productId');
        $quantity = (int)$request->getPost('quantity');
        if (!$productId || $quantity <= 0) {
            $this->SendResponse(['error' => 'Неккоректные параметры']);
            return;
        }

        $arFields = ['QUANTITY' => $quantity];
        if(CSaleBasket::Update($productId, $arFields)) {
            $this->SendResponse(['success'=> true, 'quantity' => $quantity]);
        }
        else {
            $this->SendResponse(['error'=>'Не удалось обновить количество товара']);
        }
    }

    protected function GetCart() {
        $fuserId = Fuser::getId();
        $arFilter = ['FUSER_ID' => $fuserId, 'LID' => SITE_ID, 'ORDER_ID' => NULL];
        $busket = CSaleBasket::GetList([], $arFilter);

        $items = [];
        while($item =$busket->Fetch()){
            $item['PROPS'] = [];
            $props = CSaleBasket::GetPropsList(
                [],
                ["BASKET_ID" => $item["ID"]]
            );

            while ($arProp = $props->Fetch()) {
                $item['PROPS'][] = $arProp;
            }

            $items[] = $item;
        }

        $this->SendResponse(['success' => true, 'items'=> $items]);
    }

    protected function SendResponse($data) {
        header('Content-Type: application:json');
        echo json_encode($data);
        exit;   
    }
}

$handler = new CartAjaxHandler();
$handler->handeRequest();
