@if ($errors->{ $bag ?? 'default' }->any())
    <div class="text-red-500">
        <ul>
            @foreach ($errors->{ $bag ?? 'default' }->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif