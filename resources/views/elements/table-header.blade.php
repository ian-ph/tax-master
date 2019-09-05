<th class="text-{{ $align }}">
    <a href="{{ route($route, ['uuid'=> Request()->uuid, 'sort'=>$column_name, 'sort_direction' => !Request()->sort_direction || Request()->sort_direction == 'asc' ? 'desc' : 'asc' ]) }}">
        @if($align=="left" && (Request()->sort == $column_name))
            @if(!Request()->sort_direction || Request()->sort_direction == 'asc')
                <span class="oi oi-caret-top"></span>
            @else
                <span class="oi oi-caret-bottom"></span>
            @endif
        @endif
        {{ $caption }}
        @if($align=="right" && (Request()->sort == $column_name))
            @if(!Request()->sort_direction || Request()->sort_direction == 'asc')
                <span class="oi oi-caret-top"></span>
            @else
                <span class="oi oi-caret-bottom"></span>
            @endif
        @endif
    </a>
</th>
