<!-- Custom Navbar -- Mobile-->
<div class="custom-navbar">
    <div class="custom-logo">
        <a href="{{ route('index') }}">Muslim Funeral Society</a>
    </div>
    <div class="custom-menu-icon" id="openSidebar">
        <!-- Hamburger SVG -->
        <svg width="30" height="30" viewBox="0 0 100 80" fill="#fff">
            <rect width="100" height="10"></rect>
            <rect y="30" width="100" height="10"></rect>
            <rect y="60" width="100" height="10"></rect>
        </svg>
    </div>
</div>
 <!-- Sidebar Mobile -->
 <div class="custom-sidebar" id="customSidebar">
    <div class="sidebar-inner">
        <span class="sidebar-close" id="closeSidebar">&times;</span>
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><a href="{{route('paymentsucces.public')}}">Membership Payment Status</a></li>
            <li><a href="{{ route('faq.public') }}">FAQ</a></li>
            <li><a href="{{ route('newmember.public') }}">New Membership</a></li>
            <li><a href="{{ route('rules.public') }}">Rules and Regulations</a></li>
            <li><a href="{{ route('payment.public') }}">Payment Info</a></li>
            <li><a href="{{ route('hmbc.public') }}">HMBC</a></li>
            <li><a href="{{ route('council.public') }}">What to do during a Bereavement?</a></li>
            <li><a href="{{ route('contact.public') }}">Contact Us</a></li>
            <li><a href="{{ route('about.public') }}">About Us</a></li>
        </ul>
    </div>
</div>
