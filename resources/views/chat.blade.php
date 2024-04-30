<div id="chatboxformwrapper">
<form id="chatboxmsg">
<div id="notiforb" class="orb"></div>
<div id="chatboxmsgstatus"></div>
@csrf
    @if (Auth::check())
    <div>[<input type="text" id="chatusername" value="{{ Auth::user()->username }}">]</div>
    @else
    <div>[<input type="text" id="chatusername" value="Anonymous">]</div>
    @endif
    <div>[<input type="text" id="chatboxmsginput" placeholder="Type your message...">]</div>
    <span>[</span><button type="submit" id="chatboxmsgsubmit">Send</button><span>]</span>
  </form>
</div>
<div id="chatbox">
</div>
