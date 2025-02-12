@extends('layouts.app')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@section('title', 'News Articles')

@section('content')
    <div class="mb-4">
        <form id="filterForm" class="mb-4">
            <div class="input-group">
                <input type="text" id="search" class="form-control" placeholder="Search articles">
                <select id="source" class="form-select">
                    <option value="">All Sources</option>
                    <option value="NewsAPI">NewsAPI</option>
                    <option value="New York Times">New York Times</option>
                    <option value="The Guardian">The Guardian</option>
                </select>
                <button type="button" id="filterButton" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>

    <div id="articlesContainer" class="row"></div>
    <div id="paginationContainer" class="mt-4"></div>

    <script>
        const apiEndpoint = '{{ url("/api/getarticles") }}';

        // Fetch and display articles
        const fetchArticles = (page = 1) => {
            const search = $('#search').val();
            const source = $('#source').val();

            $.ajax({
                url: apiEndpoint,
                method: 'GET',
                data: {
                    search: search,
                    source: source,
                    page: page,
                },
                success: function (response) {
                    const articles = response.data;
                    const meta = response.meta;

                    // Render articles and pagination
                    renderArticles(articles);
                    renderPagination(meta);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching articles:', error);
                    $('#articlesContainer').html('<p class="text-danger">Failed to fetch articles. Please try again later.</p>');
                },
            });
        };

        // Render articles in the container
        const renderArticles = (articles) => {
            const container = $('#articlesContainer');
            container.html('');

            if (articles.length === 0) {
                container.html('<p class="text-muted">No articles found.</p>');
                return;
            }

            articles.forEach((article) => {
                const articleHTML = `
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="${article.image_url || 'https://via.placeholder.com/150'}" class="card-img-top" alt="${article.title}">
                            <div class="card-body">
                                <h5 class="card-title">${article.title}</h5>
                                <p class="card-text">${article.description || 'No description available.'}</p>
                                <a href="${article.url}" target="_blank" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                `;
                container.append(articleHTML);
            });
        };

        // Render pagination controls
        const renderPagination = (meta) => {
            const container = $('#paginationContainer');
            container.html('');

            if (meta.total <= meta.per_page) return; // No need for pagination if total items fit on one page

            let paginationHTML = '<nav><ul class="pagination justify-content-center">';
            for (let page = 1; page <= meta.last_page; page++) {
                paginationHTML += `
                    <li class="page-item ${meta.current_page === page ? 'active' : ''}">
                        <button class="page-link" onclick="fetchArticles(${page})">${page}</button>
                    </li>
                `;
            }
            paginationHTML += '</ul></nav>';

            container.html(paginationHTML);
        };

        // Add event listeners
        $('#filterButton').on('click', () => fetchArticles());

        // Initial load
        fetchArticles();
    </script>
    
@endsection
