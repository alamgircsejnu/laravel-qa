@extends('layouts.app')

@section('content')
    <question-page :question="{{ $question }}"></question-page>
@endsection
{{--<script>--}}
    {{--import Answers from "../../js/components/Answers";--}}
    {{--export default {--}}
        {{--components: {Answers}--}}
    {{--}--}}
{{--</script>--}}