

$(document).ready(function(){
    function updateSlider() {
        const offset = -100 * currentIndex;
        $slider.css('transform', `translateX(${offset}%)`);
    }

    function sliderItemWidth() {
        return $slider.outerWidth() / currentVisibleItems;
    }

    function maxAllowedIndex() {
        return Math.max(0, totalSlides - currentVisibleItems);
    }

    const $slider = $('.slider');
    const currentVisibleItems = 1;
    const $items = $('.slider-item');
    const totalSlides = $items.length;
    let isDragging = false;
    let isAnimating = false;
    let currentTranslateX = 0;
    let dragStartX = 0, startTranslateX = 0, offsetX = 0;
    let currentIndex = 0;

    $('#slider-control-button-right').on('click', function() {
        if (currentIndex < totalSlides - 1) {
            currentIndex++;
            updateSlider();
        }
    });

     $('#slider-control-button-left').on('click', function() {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlider();
        }
    });
    $slider.on('mousedown touchstart', function(e) {
        e.preventDefault();

        if(isAnimating) return;

        isDragging = true,
        isAnimating = false;
        dragStartX = e.clientX || e.touches[0].clientX
        startTranslateX = currentIndex * (100 / currentVisibleItems);
        $slider.css('transition', 'none');
    });

    $slider.on('mousemove touchmove', function(e){
        
        e.preventDefault();
        if(!isDragging) return;
       
        const dragX = e.type === 'touchmove' ? e.originalEvent.touches[0].clientX : e.clientX;
        offsetX = dragX - dragStartX;
        const slidesMoved = (offsetX / sliderItemWidth()) * 100;
        currentTranslateX = (startTranslateX - (slidesMoved / currentVisibleItems));
      
        const maxTranslate = maxAllowedIndex() * (100 / currentVisibleItems);
        currentTranslateX = Math.max(0, Math.min(currentTranslateX, maxTranslate));
        $slider.css('transform', `translateX(-${currentTranslateX}%`);
       
        
    })

    $slider.on('mouseup touchend mouseleave', function(e){
     
        e.preventDefault();
        if(!isDragging) return;
   
        isDragging = false;
        $slider.css('transition', 'transform 0.7s ease');
       
        const threshold = sliderItemWidth() * 0.1;
        
        const sliderStep = Math.floor(Math.abs(offsetX / sliderItemWidth()));
        const remains = offsetX % sliderItemWidth();
        const delta = Math.round(Math.abs(remains) / sliderItemWidth());
        if (Math.abs(offsetX) > threshold && delta == 0){
            if(offsetX > 0){
                currentIndex > 0 ? currentIndex -- : currentIndex;
            }
            else {
                currentIndex < maxAllowedIndex() ? currentIndex++ : currentIndex;  
            }
            
        }
        else if (Math.abs(offsetX) > threshold) {
            if (offsetX > 0) {
                currentIndex = currentIndex > sliderStep + delta  ? currentIndex - sliderStep - delta : 0;
            } else {
                const predictedIndex = currentIndex + sliderStep + delta;
                currentIndex =  predictedIndex > maxAllowedIndex() ? maxAllowedIndex() : predictedIndex;   
            }
        }
        currentTranslateX = currentIndex * (100 / currentVisibleItems);
        $slider.css('transform', `translateX(-${currentTranslateX}%`);
        isAnimating = true;
        if(startTranslateX != currentTranslateX){
            $slider.one('transitionend', function(){
                    console.log('trans')
                isAnimating = false;
            });
        }
        else{
            isAnimating = false
        }

        if(offsetX != 0){
        }
        else {
            
        }
        offsetX = 0;       
    });
});