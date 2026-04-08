<@if($tier_helper->isValid())
<style>
    .tier-checkout .tier-progress {
        height: 20px;
        /* Adjust the height of the progress bar */
    }

    .tier-checkout .tier-text {
        display: inline;
        text-transform: capitalize;
    }

    .tier-checkout .tier-shape {
        padding: 20px;
    }

    .tier-checkout .tier-text-bigger {
        font-size: 2rem;
        line-height: 100%;
        text-transform: capitalize;
    }
</style>

{{-- INFLUENCE:
https://www.facebook.com/Influenster/posts/were-saying-goodbye-to-impact-scores-and-welcoming-in-our-new-tier-systemmake-su/5177588345623185/
--}}

<div class="container my-5 tier-checkout">
    <div class="card p-3">

        @if($tier_helper->tier_upgraded == 1)
        {{-- Start design for tier upgrade --}}
        <h3>CONGRATULATIONS: You upgraded to <b class="tier-text">{{ $tier_helper->tier->title }}</b></h3>
        {{-- End design for tier upgrade --}}
        @endif

        @if ($tier_helper->hasNextTier())
        {{-- Start design for progress --}}
        <div class="row align-items-end align-items-center">
            <div class="col-auto">
                <div class="tier-shape">
                    <div class="tier-text tier-text-bigger">{{ $tier_helper->tier->title }}</div>
                </div>
            </div>
            <div class="col">
                <h3>Tier Progress: </h3>
                <div class="progress tier-progress">
                    <div class="progress-bar tier-progress-bar" role="progressbar"
                        style="width: {{ $tier_helper->next_tier_percent }}%;" aria-valuenow="50" aria-valuemin="0"
                        aria-valuemax="100">{{ $tier_helper->next_tier_percent }}%
                    </div>
                </div>
                <p>${{ $tier_helper->next_tier_amount }} needed to unlock
                    <b class="tier-text">{{ $tier_helper->next_tier->title }}</b>
                </p>
            </div>
            <div class="col-auto">
                <div class="tier-shape">
                    <div class="tier-text tier-text-bigger">{{ $tier_helper->next_tier->title }}</div>
                </div>
            </div>
        </div>
        {{-- End design for progress --}}

        @else
        {{-- Start design for highest tier --}}
        <div>
            <h2>You are at the highest tier: <b class="tier-text">{{ $tier_helper->tier->title }}</b>
            </h2>
        </div>
        {{-- End design for highest tier --}}
        @endif


    </div>
</div>
@endempty
