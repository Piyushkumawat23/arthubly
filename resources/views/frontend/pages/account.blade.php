@extends('frontend.layout.app')

@section('title', 'My Account — Arthubly')

@section('content')
@verbatim
  <section class="page active" id="page-account">
    <div class="wrap">
      <div class="crumbs"><a data-nav="home">Home</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg><span class="cur">My Account</span></div>
      <div class="acct-layout">
        <aside class="acct-side" id="acctSide"></aside>
        <div class="acct-main" id="acctMain"></div>
      </div>
    </div>
  </section>
@endverbatim
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){var ordered=false;try{if(localStorage.getItem('kg_ordered')){ordered=true;localStorage.removeItem('kg_ordered');}}catch(e){}if(ordered)ACCT_VIEW='orders';renderAccount();if(ordered)setTimeout(function(){toast(null,'Order placed \u2014 thank you for supporting makers!');},300);});
</script>
@endpush
