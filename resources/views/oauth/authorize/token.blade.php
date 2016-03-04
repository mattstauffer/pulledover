<!-- todo delete - this should happen from the client once they have the code -->
<form method="post" action="{!! url('oauth/access_token') !!}">
    {{ csrf_field() }}
    <input type="hidden" name="client_id" value="1">
    <input type="hidden" name="grant_type" value="authorization_code">
    <input type="hidden" name="client_secret" value="password">
    <input type="hidden" name="redirect_uri" value="oauth/access_token">
    <input type="hidden" name="code" value="{{$code}}">

    <button type="submit" name="approve" value="1">Submit</button>
</form>