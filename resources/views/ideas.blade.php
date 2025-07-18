<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="UTF-8">
  <title>Ideas | Suitmedia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .line-clamp-3 {
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .banner {
      height: 400px;
      background-size: cover;
      background-position: center;
      background-attachment: scroll;
      clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
      position: relative;
      margin-top: 72px;
      background-blend-mode: overlay;
    background-color: rgba(0, 0, 0, 0.4);
    }
    .banner-overlay {
      background-color: rgba(0, 0, 0, 0.2);
      position: absolute;
      inset: 0;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
    }
    .bg-orange {
        background-color: #ff6600 !important;
    }
    .object-fit-cover {
        object-fit: cover;
        width: 100%;
        height: auto;
    }
    .card:hover {
        transform: scale(1.02);
        transition: transform 0.2s ease;
        }
        .card-img-top {
  background-color: #eee;
  min-height: 200px;
}


    #site-header {
  transition: top 0.3s ease, background 0.3s ease;
  backdrop-filter: blur(8px); 
  z-index: 1000;
}
    backdrop-filter: blur(8px);
  </style>
</head>
<body class="bg-light">

<!-- Header -->
<header id="site-header" class="bg-orange fixed-top shadow py-3">
  <div class="container d-flex justify-content-between align-items-center">
    <div class="fw-bold fs-4">Suitmedia</div>
    <nav class="d-flex gap-3">
      @php $active = request()->path(); @endphp
      <a href="/work" class="nav-link {{ request()->is('work') ? 'text-warning fw-bold' : 'text-muted' }}">Work</a>
      <a href="/about" class="nav-link {{ $active == 'about' ? 'text-warning fw-bold' : 'text-muted' }}">About</a>
      <a href="/services" class="nav-link {{ $active == 'services' ? 'text-warning fw-bold' : 'text-muted' }}">Services</a>
      <a href="/ideas" class="nav-link {{ request()->is('ideas') ? 'text-warning fw-bold' : 'text-muted' }}">Ideas</a>
      <a href="/careers" class="nav-link {{ $active == 'careers' ? 'text-warning fw-bold' : 'text-muted' }}">Careers</a>
      <a href="/contact" class="nav-link {{ $active == 'contact' ? 'text-warning fw-bold' : 'text-muted' }}">Contact</a>
    </nav>
  </div>
</header>

<!-- Banner -->
<section class="banner" style="
    background-image: url('{{ $bannerImage ?? 'https://source.unsplash.com/1600x600/?abstract' }}');
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center center;
">
<div class="banner-overlay">
    <h1 class="display-5 fw-bold">Ideas</h1>
    <p class="lead">Where all our great things begin</p>
  </div>
</section>

<!-- Controls -->
<form method="get" class="d-flex gap-2 justify-content-end align-items-center">
  <input type="hidden" name="page" value="{{ $currentPage }}">

  <label for="size" class="form-label mb-0 me-2">Show:</label>
  <select id="size" name="size" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
    @foreach([10, 20, 50] as $option)
      <option value="{{ $option }}" {{ $option == $pageSize ? 'selected' : '' }}>
        {{ $option }}
      </option>
    @endforeach
  </select>

  <label for="sort" class="form-label mb-0 ms-3 me-2">Sort by:</label>
  <select id="sort" name="sort" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
    <option value="-published_at" {{ $sort == '-published_at' ? 'selected' : '' }}>Newest</option>
    <option value="published_at" {{ $sort == 'published_at' ? 'selected' : '' }}>Oldest</option>
  </select>
</form>



<!-- Ideas Grid -->
<div class="container">
  <div class="row g-4">
    @forelse($ideas as $idea)
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
        <img
  src="{{ $idea['first_image'] ?? ($idea['medium_image'][0]['url'] ?? 'https://via.placeholder.com/300x200?text=No+Image') }}"
  alt="thumbnail"
  class="card-img-top object-fit-cover"
  style="aspect-ratio: 4/3;"
  loading="lazy"
  onerror="this.src='https://via.placeholder.com/300x200?text=No+Image';"
  onload="this.style.backgroundImage='none';"
  style="background: url('{{ $idea['image_loader'] }}') center center no-repeat; background-size: 32px;"
/>
          <div class="card-body">
            <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($idea['published_at'])->format('j F Y') }}</p>
            <h5 class="card-title line-clamp-3">{{ $idea['title'] }}</h5>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center text-muted">No ideas found.</div>
    @endforelse
  </div>
</div>

<!-- Pagination -->
<div class="container my-5">
  <nav class="d-flex justify-content-center">
    <ul class="pagination">
      @php $last = $meta['last_page'] ?? 1; @endphp

      @if($currentPage > 1)
        <li class="page-item">
          <a class="page-link" href="?page={{ $currentPage - 1 }}&size={{ $pageSize }}&sort={{ $sort }}">&laquo;</a>
        </li>
      @endif

      @for($i = 1; $i <= min($last, 5); $i++)
        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
          <a class="page-link" href="?page={{ $i }}&size={{ $pageSize }}&sort={{ $sort }}">{{ $i }}</a>
        </li>
      @endfor

      @if($currentPage < $last)
        <li class="page-item">
          <a class="page-link" href="?page={{ $currentPage + 1 }}&size={{ $pageSize }}&sort={{ $sort }}">&raquo;</a>
        </li>
      @endif
    </ul>
  </nav>
</div>

<!-- Header Scroll Behavior -->
<script>
let lastScroll = 0;
const header = document.getElementById('site-header');

window.addEventListener('scroll', function () {
  let currentScroll = window.scrollY;

  if (currentScroll > lastScroll) {
    header.style.top = '-100px';
  } else {
    header.style.top = '0';
    header.style.background = 'rgba(255, 255, 255, 0.9)';
  }

  if (currentScroll <= 10) {
    header.style.background = '#fff';
  }

  lastScroll = currentScroll;
});
</script>

</body>
</html>
