@extends('layouts.app')

@section('title', 'My Availabilities')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h2>My Availabilities</h2>
    <a href="{{ route('availabilities.create') }}" class="btn btn-primary">Add New Availability</a>
</div>

@if($availabilities->isEmpty())
    <div class="alert alert-info">You haven't set any availabilities yet.</div>
@else
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-availabilities" type="button" role="tab" aria-controls="all-availabilities" aria-selected="true">All</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active-availabilities" type="button" role="tab" aria-controls="active-availabilities" aria-selected="false">Active</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming-availabilities" type="button" role="tab" aria-controls="upcoming-availabilities" aria-selected="false">Upcoming</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past-availabilities" type="button" role="tab" aria-controls="past-availabilities" aria-selected="false">Past</button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="all-availabilities" role="tabpanel" aria-labelledby="all-tab">
                    @include('availabilities.partials.availability-list', ['availabilities' => $availabilities])
                </div>
                <div class="tab-pane fade" id="active-availabilities" role="tabpanel" aria-labelledby="active-tab">
                    @include('availabilities.partials.availability-list', ['availabilities' => $availabilities->filter(function($item) { 
                        return $item->status === 'available' && $item->start_time <= now() && $item->end_time >= now(); 
                    })])
                </div>
                <div class="tab-pane fade" id="upcoming-availabilities" role="tabpanel" aria-labelledby="upcoming-tab">
                    @include('availabilities.partials.availability-list', ['availabilities' => $availabilities->filter(function($item) { 
                        return $item->start_time > now(); 
                    })])
                </div>
                <div class="tab-pane fade" id="past-availabilities" role="tabpanel" aria-labelledby="past-tab">
                    @include('availabilities.partials.availability-list', ['availabilities' => $availabilities->filter(function($item) { 
                        return $item->end_time < now(); 
                    })])
                </div>
            </div>
        </div>
    </div>
@endif
@endsection