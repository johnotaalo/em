@extends('layouts.app')

@section('content')
<county-breakdown-component county="{{ $county }}" :assessments='{!! json_encode($assessments) !!}' :facilitydistribution='{!! json_encode($distributions) !!}'></county-breakdown-component>
@endsection