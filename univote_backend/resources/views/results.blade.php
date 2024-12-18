@extends('sidebar')
@section('content')

<style>
    span {
        font-size: 25px;
        font-weight: 500;
    }
</style>

<div>
    @if(!empty($stats) && count($stats) > 0)
    <div style="display: flex; align-items: center; justify-content:space-around; margin-top: 20%">
        @foreach($stats as $stat)
        <div class="stat-row">
            <div style="font-size: 30px; font-weight: 700; color: #ff4500">Vote Counts</div>
                <div style="display: flex; align-items: center; gap: 10px">
                <span>{{ $stat->can_name }}</span>
                <div style="border: 2px solid #ff4500; border-radius: 20px; height: 40px; width: 40px; justify-content: center; align-items: center; display: flex">
                    <span>{{ $stat->can_count }}</span>
                </div>
                </div>
        </div>
        <div class="stat-row">
            <div style="font-size: 30px; font-weight: 700; color: #ff4500">Leading</div>
                <div style="display: flex; align-items: center; gap: 10px">
                <span>{{ $lead_name }}</span>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <p>No statistics available.</p>
    @endif

</div>
@endsection