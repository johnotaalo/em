@extends('layouts.app')

@section('content')
<county-breakdown-component id="{{ $county_id }}" county="{{ $county }}" :assessments='{!! json_encode($assessments) !!}' :facilitydistribution='{!! json_encode($distributions) !!}' :facilities='{{ $facilities }}' :pneumoniatotals='{!! json_encode($pneumoniaTotals) !!}' :diarrhoeatotals='{!! json_encode($diarrhoeaTotals) !!}' :legacydata = '{!! json_encode($legacy) !!}' :error="{!! $error !!}"></county-breakdown-component>
@endsection