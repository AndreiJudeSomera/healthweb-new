<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MessageTest extends TestCase
{
	use RefreshDatabase;

	protected function setUp(): void
	{
		parent::setUp();
		$this->artisan('migrate');
	}

	public function test_doctor_can_message_any_user()
	{
		$docUser = User::create(['username' => 'docuser', 'email' => 'doc@example.test', 'password' => Hash::make('password'), 'role' => 0]);
		Doctor::create(['user_id' => $docUser->id, 'dr_license_no' => 'DR001', 'ptr_no' => 'PTR001']);

		$recipient = User::create(['username' => 'patientx', 'email' => 'patient1@example.test', 'password' => Hash::make('password'), 'role' => 0]);

		$this->actingAs($docUser)
			->postJson(route('messages.store'), [
				'recipient_id' => $recipient->id,
				'body' => 'Hello from doctor',
			])->assertStatus(201)->assertJson(['message' => 'Message sent']);
	}

	public function test_secretary_can_message_any_user()
	{
		$secretary = User::create(['username' => 'secretary', 'email' => 'sec@example.test', 'password' => Hash::make('password'), 'role' => 1]);
		$recipient = User::create(['username' => 'patient2', 'email' => 'patient2@example.test', 'password' => Hash::make('password'), 'role' => 0]);

		$this->actingAs($secretary)
			->postJson(route('messages.store'), [
				'recipient_id' => $recipient->id,
				'body' => 'Hello from secretary',
			])->assertStatus(201)->assertJson(['message' => 'Message sent']);
	}

	public function test_patient_cannot_message_other_patient()
	{
		$patient = User::create(['username' => 'pat1', 'email' => 'p1@example.test', 'password' => Hash::make('password'), 'role' => 0]);
		$otherPatient = User::create(['username' => 'pat2', 'email' => 'p2@example.test', 'password' => Hash::make('password'), 'role' => 0]);

		$this->actingAs($patient)
			->postJson(route('messages.store'), [
				'recipient_id' => $otherPatient->id,
				'body' => 'Hello',
			])->assertStatus(403)->assertJson(['message' => 'You are not allowed to send a message to this user']);
	}

	public function test_patient_can_message_secretary_or_admin()
	{
		$patient = User::create(['username' => 'pat3', 'email' => 'p3@example.test', 'password' => Hash::make('password'), 'role' => 0]);
		$secretary = User::create(['username' => 'secret', 'email' => 'sec2@example.test', 'password' => Hash::make('password'), 'role' => 1]);
		$admin = User::create(['username' => 'admin', 'email' => 'admin@example.test', 'password' => Hash::make('password'), 'role' => 2]);

		$this->actingAs($patient)
			->postJson(route('messages.store'), [
				'recipient_id' => $secretary->id,
				'body' => 'Hello sec',
			])->assertStatus(201);

		$this->actingAs($patient)
			->postJson(route('messages.store'), [
				'recipient_id' => $admin->id,
				'body' => 'Hello admin',
			])->assertStatus(201);
	}

	public function test_secretary_can_view_messages_page()
	{
		$secretary = User::create(['username' => 'secret3', 'email' => 'sec3@example.test', 'password' => Hash::make('password'), 'role' => 1]);
		$this->actingAs($secretary)->get(route('messages.index'))->assertStatus(200);
	}

	public function test_patient_can_view_usermessages_page()
	{
		$patient = User::create(['username' => 'pat4', 'email' => 'p4@example.test', 'password' => Hash::make('password'), 'role' => 0]);
		$this->actingAs($patient)->get(route('usermessages.index'))->assertStatus(200);
	}

	public function test_patient_search_excludes_patients()
	{
		$patient = User::create(['username' => 'pat5', 'email' => 'p5@example.test', 'password' => Hash::make('password'), 'role' => 0]);
		// create another patient and a secretary
		$patient2 = User::create(['username' => 'pat6', 'email' => 'p6@example.test', 'password' => Hash::make('password'), 'role' => 0]);
		$secretary = User::create(['username' => 'sec', 'email' => 'sec4@example.test', 'password' => Hash::make('password'), 'role' => 1]);

		$res = $this->actingAs($patient)->get('/api/users/search?q=pat');
		$res->assertStatus(200);
		$data = $res->json('data');
		foreach ($data as $u) {
			$this->assertNotEquals(0, $u['role']);
		}
	}

	public function test_doctor_search_includes_patients()
	{
		$doctorUser = User::create(['username' => 'docuser2', 'email' => 'doc2@example.test', 'password' => Hash::make('password'), 'role' => 2]);
		Doctor::create(['user_id' => $doctorUser->id, 'dr_license_no' => 'DR002', 'ptr_no'=> 'PTR002']);
		$patient2 = User::create(['username' => 'psearch', 'email' => 'psearch@example.test', 'password' => Hash::make('password'), 'role' => 0]);

		$res = $this->actingAs($doctorUser)->get('/api/users/search?q=psearch');
		$res->assertStatus(200);
		$this->assertCount(1, $res->json('data'));
		$this->assertEquals('psearch', $res->json('data.0.username'));
	}
}

