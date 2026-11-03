@if (app('scroller'))
<div class="scroll-to-top">
    <div class="scroll-to-top-inner">
        <a href="javascript:void(0);" onclick="document.querySelector('#scroller-top').scrollIntoView({behavior: 'smooth'});"><i class="fas fa-arrow-up fa-2x up-color"></i></a>
    </div>
</div>
@endif