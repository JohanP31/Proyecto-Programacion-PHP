@if (session('status') || session()->has('message'))
    <div class="alert alert-{{ session('message.type') === 'error' ? 'danger' : 'success' }}" role="status">
        {{ session('status') ?? session('message.content') ?? session('message') }}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif
