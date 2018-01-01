<template>
	<div class="card">
		<div class="card-header">
			<div class="row align-items-center">
				<div class="col">
					<h4 class="card-header-title">Users</h4>
				</div>

				<div class="col-auto">
					<a href="/user-management/add" class="btn btn-sm btn-white">
						Add User
					</a>
				</div>
			</div>
		</div>
		<div class="table-responsive mb-0">
			<v-server-table
			url="/api/users"
			ref="users-table"
			:columns="table.columns"
			:options="table.options"
			>
				<template slot="User" slot-scope="data">
					<a href="profile-posts.htmL" class="avatar avatar-xs d-inline-block mr-2">
						<img src="/img/avatars/profiles/avatar-1.jpg" alt="..." class="avatar-img rounded-circle">
					</a>
					<span>{{ data.row.name }}</span>
				</template>

				<template slot="Email" slot-scope="data">
					{{ data.row.email }}
				</template>

				<template slot="User Since" slot-scope="data">
					{{ data.row.created_at | moment('Do MMMM, YYYY') }}
				</template>

				<template slot="Last Updated" slot-scope="data">
					{{ data.row.updated_at | moment('Do MMMM, YYYY')  }}
				</template>

				<template slot="User Type" slot-scope="data">
					{{ data.row.user_type | formatUserType }}
				</template>

				<template slot="Status" slot-scope="data">
					<div class="badge" v-bind:class="{ 'badge-soft-success': data.row.status, 'badge-soft-danger': !data.row.status }">
						<span v-if="data.row.status">Active</span><span v-else>Inactive</span>
					</div>
				</template>

				<template slot = "Actions" slot-scope="data">
					<a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						<i class="fe fe-more-vertical"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a v-bind:href='"/user-management/edit/" + data.row.id' class="dropdown-item">
							Edit
						</a>
						<a class="dropdown-item" v-on:click="resetPassword(data.row.id)">
							Reset Password
						</a>
						<a href="#" class="dropdown-item" v-on:click="changeStatus(data.row.id)">
							Change Status
						</a>
					</div>
				</template>
			</v-server-table>
		</div>
	</div>
</template>

<script type="text/javascript">
	export default {
		data() {
			return {
				table: {
					columns: [
						'User', 'Email', 'User Since', 'Last Updated', 'User Type', 'Status', 'Actions'
					],
					options: {
						filterable: false,
						perPageValues: [],
						columnsClasses: {
							'Actions': 'text-right'
						},
						sortIcon: 'fa fa-sort'
					}
				}
			}
		},
		mounted(){
			var table = $('table.VueTables__table');
			table.removeClass('VueTables__table table-striped table-bordered table-hover')
			table.addClass('table-sm table-nowrap card-table');
		},
		methods: {
			changeStatus(id){
				this.$swal({
					icon: 'warning',
					title: "Change User Status?",
					text: "Are you sure you want to change the user's status? This will affect how they log in to the application.",
					buttons: true,
					dangerMode: true
				})
				.then((changeStatus) => {
					if (changeStatus) {
						// Proceed to change status
						axios.post('/api/users/update/status/', {id: id})
						.then((res) => {
							this.$swal("Success", "Successfully changed user status", "success");
							this.$refs['users-table'].refresh()
						})
						.catch((error) => {
							this.$swal("Error", "Could not update user status", "error");
						})
					}else{
						this.$swal("No change has been made");
					}
				})
			},
			resetPassword(id){
				this.$swal({
					icon: 'warning',
					title: 'Reset User Password',
					text: "Are you sure you want to reset the user's password?",
					buttons: true,
					dangerMode: true
				}).then((reset) => {
					if (reset) {
						axios.put('/api/users/password/reset', {id: id})
						.then(res => {
							this.$swal("Success", "Successfully sent a password reset email to user", "success");
						})
						.catch(error => {
							this.$swal("Error", "Could not send password reset email to user", "error");
						})
					}else{
						this.$swal("Password reset email not sent");
					}
				})
			}
		}
	}
</script>