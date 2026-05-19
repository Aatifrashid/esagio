<?php

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\User;
use App\Filament\Clinic\Resources\TeamMemberResource;

beforeEach(function () {
    $this->clinic = Clinic::factory()->create(['name' => 'Test Clinic']);

    $this->owner = User::factory()->clinicOwner()->create([
        'clinic_id' => $this->clinic->id,
        'name' => 'Dr Owner',
    ]);

    $this->admin = User::factory()->create([
        'clinic_id' => $this->clinic->id,
        'role' => 'clinic_admin',
        'name' => 'Admin User',
    ]);

    $this->salesStaff = User::factory()->salesStaff()->create([
        'clinic_id' => $this->clinic->id,
        'name' => 'Sales Person',
    ]);

    $this->dentist = User::factory()->dentist()->create([
        'clinic_id' => $this->clinic->id,
        'name' => 'Dr Dentist',
    ]);

    $this->viewer = User::factory()->create([
        'clinic_id' => $this->clinic->id,
        'role' => 'viewer',
        'name' => 'View Only',
    ]);

    // Other clinic
    $this->otherClinic = Clinic::factory()->create(['name' => 'Other Clinic']);
    $this->otherUser = User::factory()->clinicOwner()->create([
        'clinic_id' => $this->otherClinic->id,
        'name' => 'Other Owner',
    ]);
});

// ─── Role helper methods ───

test('clinic owner is detected correctly', function () {
    expect($this->owner->isClinicOwner())->toBeTrue()
        ->and($this->admin->isClinicOwner())->toBeFalse()
        ->and($this->salesStaff->isClinicOwner())->toBeFalse()
        ->and($this->dentist->isClinicOwner())->toBeFalse()
        ->and($this->viewer->isClinicOwner())->toBeFalse();
});

test('clinic admin check includes owner and admin roles', function () {
    expect($this->owner->isClinicAdmin())->toBeTrue()
        ->and($this->admin->isClinicAdmin())->toBeTrue()
        ->and($this->salesStaff->isClinicAdmin())->toBeFalse()
        ->and($this->dentist->isClinicAdmin())->toBeFalse()
        ->and($this->viewer->isClinicAdmin())->toBeFalse();
});

test('sales staff is detected correctly', function () {
    expect($this->salesStaff->isSalesStaff())->toBeTrue()
        ->and($this->owner->isSalesStaff())->toBeFalse()
        ->and($this->dentist->isSalesStaff())->toBeFalse();
});

test('restricted roles are identified correctly', function () {
    expect($this->salesStaff->isRestrictedRole())->toBeTrue()
        ->and($this->dentist->isRestrictedRole())->toBeTrue()
        ->and($this->viewer->isRestrictedRole())->toBeTrue()
        ->and($this->owner->isRestrictedRole())->toBeFalse()
        ->and($this->admin->isRestrictedRole())->toBeFalse();
});

test('super admin is detected correctly', function () {
    $superAdmin = User::factory()->superAdmin()->create();
    expect($superAdmin->isSuperAdmin())->toBeTrue()
        ->and($this->owner->isSuperAdmin())->toBeFalse();
});

// ─── Panel access ───

test('all clinic users can access clinic panel', function () {
    $panel = \Filament\Facades\Filament::getPanel('clinic');

    expect($this->owner->canAccessPanel($panel))->toBeTrue()
        ->and($this->admin->canAccessPanel($panel))->toBeTrue()
        ->and($this->salesStaff->canAccessPanel($panel))->toBeTrue()
        ->and($this->dentist->canAccessPanel($panel))->toBeTrue()
        ->and($this->viewer->canAccessPanel($panel))->toBeTrue();
});

test('only super admin can access super panel', function () {
    $superAdmin = User::factory()->superAdmin()->create();
    $panel = \Filament\Facades\Filament::getPanel('super');

    expect($superAdmin->canAccessPanel($panel))->toBeTrue()
        ->and($this->owner->canAccessPanel($panel))->toBeFalse()
        ->and($this->dentist->canAccessPanel($panel))->toBeFalse();
});

// ─── Team management access ───

test('owner can access team management', function () {
    $this->actingAs($this->owner);
    expect(TeamMemberResource::canAccess())->toBeTrue();
});

test('admin can access team management', function () {
    $this->actingAs($this->admin);
    expect(TeamMemberResource::canAccess())->toBeTrue();
});

test('sales staff cannot access team management', function () {
    $this->actingAs($this->salesStaff);
    expect(TeamMemberResource::canAccess())->toBeFalse();
});

test('dentist cannot access team management', function () {
    $this->actingAs($this->dentist);
    expect(TeamMemberResource::canAccess())->toBeFalse();
});

test('viewer cannot access team management', function () {
    $this->actingAs($this->viewer);
    expect(TeamMemberResource::canAccess())->toBeFalse();
});

// ─── Team list page HTTP access ───

test('owner can load team list page', function () {
    $this->actingAs($this->owner)
        ->get('/dashboard/team')
        ->assertOk();
});

test('admin can load team list page', function () {
    $this->actingAs($this->admin)
        ->get('/dashboard/team')
        ->assertOk();
});

test('sales staff gets forbidden on team list page', function () {
    $this->actingAs($this->salesStaff)
        ->get('/dashboard/team')
        ->assertForbidden();
});

test('dentist gets forbidden on team list page', function () {
    $this->actingAs($this->dentist)
        ->get('/dashboard/team')
        ->assertForbidden();
});

test('viewer gets forbidden on team list page', function () {
    $this->actingAs($this->viewer)
        ->get('/dashboard/team')
        ->assertForbidden();
});

test('unauthenticated user gets redirected from team page', function () {
    $this->get('/dashboard/team')
        ->assertRedirect();
});

// ─── Multi-tenancy: team list scoping ───

test('team list only shows users from same clinic', function () {
    $this->actingAs($this->owner);

    $query = TeamMemberResource::getEloquentQuery();
    $users = $query->pluck('id')->toArray();

    expect($users)->toContain($this->owner->id)
        ->toContain($this->admin->id)
        ->toContain($this->salesStaff->id)
        ->toContain($this->dentist->id)
        ->toContain($this->viewer->id)
        ->not->toContain($this->otherUser->id);
});

test('super admin users are hidden from team list', function () {
    $superAdmin = User::factory()->superAdmin()->create();

    $this->actingAs($this->owner);
    $query = TeamMemberResource::getEloquentQuery();
    $users = $query->pluck('id')->toArray();

    expect($users)->not->toContain($superAdmin->id);
});

// ─── Team create/edit HTTP ───

test('owner can load create team member page', function () {
    $this->actingAs($this->owner)
        ->get('/dashboard/team/create')
        ->assertOk();
});

test('dentist cannot load create team member page', function () {
    $this->actingAs($this->dentist)
        ->get('/dashboard/team/create')
        ->assertForbidden();
});

test('owner can load edit page for team member', function () {
    $this->actingAs($this->owner)
        ->get("/dashboard/team/{$this->dentist->id}/edit")
        ->assertOk();
});

test('owner cannot edit user from another clinic', function () {
    $this->actingAs($this->owner)
        ->get("/dashboard/team/{$this->otherUser->id}/edit")
        ->assertNotFound();
});

// ─── DATA SCOPING: Restricted roles only see their own patients ───

test('owner sees all clinic patients', function () {
    $p1 = Patient::factory()->create(['clinic_id' => $this->clinic->id, 'assigned_to' => $this->dentist->id]);
    $p2 = Patient::factory()->create(['clinic_id' => $this->clinic->id, 'assigned_to' => $this->salesStaff->id]);
    $p3 = Patient::factory()->create(['clinic_id' => $this->clinic->id, 'assigned_to' => null]);

    $this->actingAs($this->owner);
    $patients = Patient::all();

    expect($patients->pluck('id')->toArray())
        ->toContain($p1->id)
        ->toContain($p2->id)
        ->toContain($p3->id);
});

test('admin sees all clinic patients', function () {
    $p1 = Patient::factory()->create(['clinic_id' => $this->clinic->id, 'assigned_to' => $this->dentist->id]);
    $p2 = Patient::factory()->create(['clinic_id' => $this->clinic->id, 'assigned_to' => $this->salesStaff->id]);

    $this->actingAs($this->admin);
    $patients = Patient::all();

    expect($patients->pluck('id')->toArray())
        ->toContain($p1->id)
        ->toContain($p2->id);
});

test('sales staff only sees their own assigned patients and unassigned', function () {
    $mine = Patient::factory()->create(['clinic_id' => $this->clinic->id, 'assigned_to' => $this->salesStaff->id]);
    $others = Patient::factory()->create(['clinic_id' => $this->clinic->id, 'assigned_to' => $this->dentist->id]);
    $unassigned = Patient::factory()->create(['clinic_id' => $this->clinic->id, 'assigned_to' => null]);

    $this->actingAs($this->salesStaff);
    $patients = Patient::all();
    $ids = $patients->pluck('id')->toArray();

    expect($ids)->toContain($mine->id)
        ->toContain($unassigned->id)
        ->not->toContain($others->id);
});

test('dentist only sees their own assigned patients and unassigned', function () {
    $mine = Patient::factory()->create(['clinic_id' => $this->clinic->id, 'assigned_to' => $this->dentist->id]);
    $others = Patient::factory()->create(['clinic_id' => $this->clinic->id, 'assigned_to' => $this->salesStaff->id]);
    $unassigned = Patient::factory()->create(['clinic_id' => $this->clinic->id, 'assigned_to' => null]);

    $this->actingAs($this->dentist);
    $patients = Patient::all();
    $ids = $patients->pluck('id')->toArray();

    expect($ids)->toContain($mine->id)
        ->toContain($unassigned->id)
        ->not->toContain($others->id);
});

test('viewer only sees their own assigned patients and unassigned', function () {
    $mine = Patient::factory()->create(['clinic_id' => $this->clinic->id, 'assigned_to' => $this->viewer->id]);
    $others = Patient::factory()->create(['clinic_id' => $this->clinic->id, 'assigned_to' => $this->dentist->id]);

    $this->actingAs($this->viewer);
    $patients = Patient::all();
    $ids = $patients->pluck('id')->toArray();

    expect($ids)->toContain($mine->id)
        ->not->toContain($others->id);
});

test('other clinic patients are never visible regardless of role', function () {
    $otherPatient = Patient::factory()->create(['clinic_id' => $this->otherClinic->id, 'assigned_to' => $this->otherUser->id]);

    foreach ([$this->owner, $this->admin, $this->salesStaff, $this->dentist, $this->viewer] as $user) {
        $this->actingAs($user);
        $ids = Patient::all()->pluck('id')->toArray();
        expect($ids)->not->toContain($otherPatient->id);
    }
});

// ─── Dashboard access ───

test('all active clinic users can access dashboard', function () {
    foreach ([$this->owner, $this->admin, $this->salesStaff, $this->dentist, $this->viewer] as $user) {
        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk();
    }
});

// ─── Patients relationship ───

test('user patients relationship returns assigned patients', function () {
    Patient::factory()->count(2)->create(['clinic_id' => $this->clinic->id, 'assigned_to' => $this->dentist->id]);
    Patient::factory()->create(['clinic_id' => $this->clinic->id, 'assigned_to' => $this->salesStaff->id]);

    expect($this->dentist->patients)->toHaveCount(2)
        ->and($this->salesStaff->patients)->toHaveCount(1)
        ->and($this->viewer->patients)->toHaveCount(0);
});

// ─── Role constants ───

test('role constants match expected values', function () {
    expect(User::ROLE_SUPER_ADMIN)->toBe('super_admin')
        ->and(User::ROLE_CLINIC_OWNER)->toBe('clinic_owner')
        ->and(User::ROLE_CLINIC_ADMIN)->toBe('clinic_admin')
        ->and(User::ROLE_DENTIST)->toBe('dentist')
        ->and(User::ROLE_SALES_STAFF)->toBe('sales_staff')
        ->and(User::ROLE_VIEWER)->toBe('viewer');
});

// ─── Legacy treatment_coordinator still works ───

test('legacy treatment_coordinator role is treated as restricted', function () {
    $legacy = User::factory()->treatmentCoordinator()->create([
        'clinic_id' => $this->clinic->id,
    ]);

    expect($legacy->isRestrictedRole())->toBeTrue()
        ->and($legacy->isSalesStaff())->toBeTrue()
        ->and($legacy->isClinicAdmin())->toBeFalse();
});
