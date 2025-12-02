@props(['action', 'method' => 'POST', 'title' => null, 'sections' => []])

<div class="modern-form">
    @if($title)
        <div class="mb-4">
            <h3 class="page-title">{{ $title }}</h3>
        </div>
    @endif

    <form action="{{ $action }}" method="{{ $method === 'GET' ? 'GET' : 'POST' }}" {{ $attributes }}>
        @if($method !== 'GET')
            @csrf
            @if($method !== 'POST')
                @method($method)
            @endif
        @endif

        @if(!empty($sections))
            @foreach($sections as $section)
                <div class="form-section">
                    @if(isset($section['title']))
                        <h5 class="form-section-title">
                            @if(isset($section['icon']))
                                <i class="bi {{ $section['icon'] }}"></i>
                            @endif
                            {{ $section['title'] }}
                        </h5>
                    @endif
                    {{ $section['content'] ?? '' }}
                </div>
            @endforeach
        @else
            {{ $slot }}
        @endif

        @if(isset($footer))
            <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
                {{ $footer }}
            </div>
        @endif
    </form>
</div>
