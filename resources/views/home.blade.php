@extends('layout')

@section('content')
<div class="container">
    <h1>News Aggregator</h1>
    <div id="articles"></div>
</div>

<script>
    fetch('/api/articles')
        .then(response => response.json())
        .then(data => {
            let articlesHtml = '';
            data.data.forEach(article => {
                articlesHtml += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">${article.title}</h5>
                            <p class="card-text">${article.content}</p>
                            <a href="${article.url}" class="btn btn-primary" target="_blank">Read More</a>
                        </div>
                    </div>
                `;
            });
            document.getElementById('articles').innerHTML = articlesHtml;
        });
</script>
@endsection
