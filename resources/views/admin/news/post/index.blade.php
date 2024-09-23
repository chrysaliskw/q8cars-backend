<x-admin-layout title="News">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">News</li>
    </x-slot>
    {{-- <div class="row">
            <div class="col-sm-12">
                <!-- <h4 class="pull-left page-title">News</h4> -->
                <ol class="breadcrumb pull-right">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="active">News</li>
                </ol>
            </div>
        </div>--}}
    <x-crud-index title="" createUrl="{{ route('admin.news.create') }}" createButtonText="Add News">
        <div class="row">
            <div class="col-lg-12">
                {!! $grid->render() !!}
            </div>
        </div>
    </x-crud-index>
    <x-slot name="scripts">

        {!! $grid->scripts() !!}
      
    </x-slot>
</x-admin-layout>
