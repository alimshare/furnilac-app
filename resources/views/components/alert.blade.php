@if(session('alert'))
    <div class="alert alert-{{ (!in_array(session('alert.type'),array('danger','info','success'))) ? 'info' : session('alert.type')}} alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ session('alert.message') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif