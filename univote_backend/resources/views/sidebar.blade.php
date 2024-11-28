<style>
    .nav-link {
        color: #fff;
    }
</style>

<div class="d-flex flex-column flex-shrink-0 p-3 bg-dark" style="width: 250px; height: 100vh;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
        <span class="fs-4">Univote</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
        </li>
        <li>
            <a href="" class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}">
                Services
            </a>
        </li>
        <li>
            <a href="" class="nav-link {{ request()->routeIs('blog') ? 'active' : '' }}">
                Blog
            </a>
        </li>
        <li>
            <a href="" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                Contact
            </a>
        </li>
    </ul>
    <hr>
    <div>
        <a href="" class="btn btn-outline-danger w-100">
            Logout
        </a>
    </div>
</div>
