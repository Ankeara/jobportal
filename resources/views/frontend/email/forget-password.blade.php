<h1>Hello {{ $mailData['user']->name }}</h1>
<p>Click here to change your password.</p>

<a href="{{ route('account.resetPassword', $mailData['token']) }}">Click here</a>
