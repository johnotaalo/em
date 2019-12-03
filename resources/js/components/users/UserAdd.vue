<template>
	<div class="row justify-content-center">
		<div class="col-12 col-lg-10 col-xl-8">
			<div class="header mt-md-5">
				<div class="header-body">
					<div class="row align-items-center">
						<div class="col">

						<!-- Pretitle -->
						<h6 class="header-pretitle">
							Add User
						</h6>
						<!-- Title -->
						<h1 class="header-title">
							User Management
						</h1>
						</div>
					</div>
				</div>
				<b-form class="mb-4 mt-5" @submit.stop.prevent="onSubmit">
					<div class="row">
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label>First name</label>
								<b-input v-model="$v.form.first_name.$model" :state="$v.form.first_name.$dirty ? !$v.form.first_name.$error : null" aria-describedby="input-1-live-feedback"/>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label>Last name</label>
								<b-input v-model="$v.form.last_name.$model" :state="$v.form.last_name.$dirty ? !$v.form.last_name.$error : null" aria-describedby="input-1-live-feedback"/>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label class="mb-1">
									Email address
								</label>
								<small class="form-text text-muted">
									This is what the user shall use to log in to the system.
								</small>
								<b-input v-model="$v.form.email.$model" :state="$v.form.email.$dirty ? !$v.form.email.$error : null" aria-describedby="input-1-live-feedback"/>
							</div>
						</div>

						<div class="col-12 col-md-6">
							<div class="form-group">
								<label>User Type</label>
								<b-select v-model = "$v.form.user_type.$model" :options="userTypes" :state="$v.form.user_type.$dirty ? !$v.form.user_type.$error : null" aria-describedby="input-1-live-feedback"></b-select>
							</div>
						</div>
					</div>
					<hr class="mt-4 mb-5">
					<div class="row">
						<div class="col-12 col-md-6">
							<label class="mb-1">
								Status
							</label>
							<small class="form-text text-muted">This will determine whether the user is active or not.</small>
							<div class="row">
								<div class="col-auto">
									<b-form-checkbox v-model="form.status" name="check-button" switch></b-form-checkbox>
								</div>
								<div class="col ml-n2">
									<small class="text-muted">
										<span v-if="form.status">Active</span>
										<span v-else>Not Active</span>
									</small>
								</div>
							</div>
						</div>
					</div>

					<div class="row mt-5">
						<div class="col-12 col-md-6">
							<button type="submit" class="btn btn-primary lift" :disabled="$v.form.$invalid">Create User</button>
						</div>
					</div>
				</b-form>
			</div>
		</div>
	</div>
</template>

<script type="text/javascript">
	import Form from '../../core/Form'
	import { validationMixin } from 'vuelidate'
	import { required, minLength, email } from 'vuelidate/lib/validators'
	export default {
		mixins: [validationMixin],
		data(){
			return {
				form: new Form({
					first_name: "",
					last_name: "",
					email: "",
					user_type: null,
					status: true
				}),
				userTypes: [{
					value: null, text: "Please select an option"
				},
				{
					value: 'admin', text: 'Administrator'
				}, {
					value: 'chai-admin', text: 'CHAI Administrator'
				}]
			}
		},
		validations: {
			form: {
				first_name: { required },
				last_name: { required },
				email: { required, email,
					isUnique(value){
						if (value === '') return true;

						return new Promise((resolve, reject) => {
							axios.post('/api/users/validate/email', { email: value })
							.then(res => {
								if (res.data.exists)
								reject("This email account is already taken")
								else
									resolve("Email not taken")
							})
							.catch(error => {
								reject(error)
							})
						})
					} 
				},
				user_type: { required }
			}
		},
		methods: {
			onSubmit: function(){
				this.$v.form.$touch()
				if (this.$v.form.$anyError) {
					return
				}
				this.form.post('/api/users/add');
			}
		}
	}
</script>