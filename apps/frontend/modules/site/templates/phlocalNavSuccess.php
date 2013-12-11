<h1 class="page-title">PHLocal Navigation</h1>
<div class="slot">
  <p>Welcome to hackish but easy navigation editing! Format is:</p>
  <div class="slot">
    &lt;relative url&gt;,&lt;navigation title&gt;,&lt;page title&gt;,&lt;enabled filters&gt;
  </div>
  <p>To make a navigation item a child, prepend it with a "-". Here are some sample items:</p>
  <div>
    /event/permalink?event_type=2&discipline=1&location=11,Exhibits,Exhibits in Philadelphia,discipline&location<br/>
    -/event/permalink?event_type=2&discipline=1&location=25,Center City,Exhibits in Center City,discipline&tag=Artwork Descriptors
  </div>
</div>
<?php a_slot('phlocal-nav', 'aText', array(
    'slug' => $_site->getGlobalVirtualPageSlug(),
    'use_phlocal_nav_validator' => true
)) ?>