@extends('layouts.app')

@section('content')
@if(!isset($error))
<county-breakdown-component id="{{ $county_id }}" county="{{ $county }}" :assessments='{!! json_encode($assessments) !!}' :facilitydistribution='{!! json_encode($distributions) !!}' :facilities='{{ $facilities }}' :pneumoniatotals='{!! json_encode($pneumoniaTotals) !!}' :diarrhoeatotals='{!! json_encode($diarrhoeaTotals) !!}' :legacydata = '{!! json_encode($legacy) !!}'></county-breakdown-component>
@else
<no-data-component></no-data-component>
@endif
@endsection