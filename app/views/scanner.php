@if ((app('plantrec_enable')) && (app('plantrec_quickscan')))
<div class="quickscan">
    <div class="quickscan-inner">
        <a href="javascript:void(0);" onclick="document.getElementById('quickscan-file-input').click();"><i id="quickscan-action-icon" class="fas fa-microscope fa-xl up-color"></i></a>
    </div>
</div>

<div>
    <form id="quickscan-form" class="is-hidden" method="POST" action="{{ url('/plants/details/identify') }}" enctype="multipart/form-data">
        @csrf

        <input type="file" name="photo" id="quickscan-file-input" accept="image/*" onchange="document.getElementById('quickscan-action-icon').classList.remove('fa-microscope'); document.getElementById('quickscan-action-icon').classList.add('fa-spinner'); document.getElementById('quickscan-action-icon').classList.add('fa-spin'); window.vue.quickPlantRecognition('quickscan-form', 'quickscan-action-icon', 'quickscan-results');"/>
    </form>
</div>
@endif