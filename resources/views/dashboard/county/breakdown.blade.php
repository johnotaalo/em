@extends('layouts.app')

@section('content')
<county-breakdown-component county="{{ $county }}" :assessments='{!! json_encode($assessments) !!}' :facilitydistribution='{!! json_encode($distributions) !!}' :facilities='{{ $facilities }}' :pneumoniatotals='{!! json_encode($pneumoniaTotals) !!}' :diarrhoeatotals='{!! json_encode($diarrhoeaTotals) !!}'></county-breakdown-component>
@endsection