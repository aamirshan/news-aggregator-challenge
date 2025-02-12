@extends('layouts.app')

@section('title', 'News Articles')

@section('content')
    <form method="GET" action="{{ route('articles.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search articles">
            <select name="source" class="form-select">
                <option value="">All Sources</option>
                <option value="NewsAPI" {{ request('source') == 'NewsAPI' ? 'selected' : '' }}>NewsAPI</option>
                <option value="New York Times" {{ request('source') == 'New York Times' ? 'selected' : '' }}>New York Times</option>
                <option value="The Guardian" {{ request('source') == 'The Guardian' ? 'selected' : '' }}>The Guardian</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    <div class="row">
        @foreach ($articles as $article)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $article->image_url ?? 'https://via.placeholder.com/150' }}" class="card-img-top" alt="{{ $article->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $article->title }}</h5>
                        <p class="card-text">{{ $article->description }}</p>
                        <a href="{{ $article->url }}" target="_blank" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $articles->links() }}
@endsection
