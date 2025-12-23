<div class="stats-cards">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-info">
            <h3>{{$verfied_user}}</h3>
            <p>{{ __('dashboard.verified_users') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-boxes"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $all_product }}</h3>
            <p>{{ __('dashboard.total_products') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-user-plus"></i>
        </div>
        <div class="stat-info">
            <h3>{{$user_last_month}}</h3>
            <p>{{ __('dashboard.new_users_last_month') }}</p>
        </div>
    </div>


</div>