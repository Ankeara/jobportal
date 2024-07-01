<h1>Hello {{ $mailData['employer']->name }}</h1>
<h1>Job title: {{ $mailData['job']->title }}</h1>

<p>Employee details: </p>
<p>Name : {{ $mailData['user']->name }}</p>
<p>Email : {{ $mailData['user']->email }}</p>
<p>Mobile No : {{ $mailData['user']->mobile }}</p>
