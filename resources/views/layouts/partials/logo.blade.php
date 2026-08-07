<div class="row text-center">
    @if (file_exists(public_path('uploads/logo.svg')))
        <div class="col-xs-12">
            <img
                src="/uploads/logo.svg"
                class="img-rounded"
                alt="Logo"
                style="max-width: 180px; width: 100%; height: auto; margin-bottom: 18px"
            />
        </div>
    @elseif (file_exists(public_path('uploads/logo.png')))
        <div class="col-xs-12">
            <img
                src="/uploads/logo.png"
                class="img-rounded"
                alt="Logo"
                width="150"
                style="margin-bottom: 30px"
            />
        </div>
    @else
        <h1 class="text-center page-header">{{ config('app.name', 'ultimatePOS') }}</h1>
    @endif
</div>
