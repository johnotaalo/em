@extends('layouts.app')

@section('content')
<temporary-data-component {!! $isLegacy ? ':islegacy = "true"' : '' !!}></temporary-data-component>
@endsection