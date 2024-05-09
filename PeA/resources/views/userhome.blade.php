@if (auth()->check())
Está log olá {{auth()->user()->name}}


<a href="{{route('user.logout')}}"> <button>Logout</button></a>

@endif