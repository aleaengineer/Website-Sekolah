{{ '<?xml version="1.0" encoding="UTF-8"?>' }}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach (['', 'profil', 'akademik', 'berita', 'pengumuman', 'agenda', 'galeri', 'ppdb', 'kontak'] as $path)
    <url>
        <loc>{{ url($path) }}</loc>
        <lastmod>{{ \Carbon\Carbon::parse($staticLastmod)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>{{ $path === '' ? '1.0' : '0.8' }}</priority>
    </url>
    @endforeach
    @foreach ($news as $article)
    <url>
        <loc>{{ route('news.show', $article) }}</loc>
        <lastmod>{{ $article->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    @foreach ($agendas as $agenda)
    <url>
        <loc>{{ route('agendas.show', $agenda) }}</loc>
        <lastmod>{{ $agenda->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
</urlset>
