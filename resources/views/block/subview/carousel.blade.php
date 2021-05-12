<div id="carousel-photos" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        @foreach ($photos as $photo)
            <li data-target="#carousel-photos" data-slide-to="{{ $loop->index }}" class="{{ !$loop->index ? 'active' : ''}}"></li>
        @endforeach
    </ol>
    <div class="carousel-inner">
        @foreach ($photos as $photo)
            <div class="carousel-item {{ !$loop->index ? 'active' : ''}}">
                <img class="d-block w-100" src="{{ $photo->preview }}" alt="{{ $photo->name }}">
            </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#carousel-photos" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carousel-photos" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
