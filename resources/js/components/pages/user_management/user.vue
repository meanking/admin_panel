<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Users <Button @click="addModal=true"><Icon type="md-add" /> Add user</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Username</th>
								<th>First name</th>
								<th>Last name</th>
								<th>Email</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->                                
								<!-- FILTER -->
							<tr>
								<td>&nbsp;</td>
								<td>
                                    <Input suffix="ios-search" v-model="searchData.username" placeholder="Search by username" style="width: auto" />
                                </td>
								<td>
                                    <Input suffix="ios-search" v-model="searchData.firstname" placeholder="Search by first name" style="width: auto" />
                                </td>
								<td>                                    
                                    <Input suffix="ios-search" v-model="searchData.lastname" placeholder="Search by last name" style="width: auto" />
                                </td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
								<!-- FILTER -->
								<!-- ITEMS -->
							<tr v-for="(user, i) in users" :key="i">
								<td>{{user.id}}</td>
								<td>{{user.username}}</td>
								<td>{{user.firstname}}</td>
								<td>{{user.lastname}}</td>
								<td>{{user.email}}</td>
								<td>
									<Button type="info" @click="showEditModal(user)">Edit</Button>
                                    <Button type="error" @click="showDeleteModal(user, i)">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->


						</table>
					</div>
				</div>
                <Page :total="total" :current="page" @on-change="handlePage" />

                <!-- User adding modal -->
                <Modal
                    v-model="addModal"
                    title="Add user"
                    :mask-closable="false"
                    :closable="false"
                    >
                    
                    <Input v-model="data.username" placeholder="Username" maxlength="20" show-word-limit />
                    <Input v-model="data.firstname" placeholder="First name" maxlength="60" show-word-limit style="margin-top: 10px;" />
                    <Input v-model="data.lastname" placeholder="Last name" maxlength="60" show-word-limit style="margin-top: 10px;" />
                    <Input v-model="data.email" placeholder="Email" type="email" style="margin-top: 10px;" />
                    <Input v-model="data.password" placeholder="Password" type="password" style="margin-top: 10px;" />
                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Cancel</Button>
                        <Button type="primary" @click="addUser" :disabled="isAdding" :loading="isAdding">{{ isAdding? 'Adding ...': 'Add user'}}</Button>  
                    </div>
                </Modal>

                <!-- User edit modal -->
                <Modal
                    v-model="editModal"
                    title="Edit user"
                    :mask-closable="false"
                    :closable="false"
                    >
                    
                    <Input v-model="selectedUser.username" maxlength="20" show-word-limit />
                    <Input v-model="selectedUser.firstname" maxlength="60" show-word-limit style="margin-top: 10px;" />
                    <Input v-model="selectedUser.lastname" maxlength="60" show-word-limit style="margin-top: 10px;" />
                    <Input v-model="selectedUser.email" type="email" style="margin-top: 10px;" />
                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Cancel</Button>
                        <Button type="primary" @click="editUser" :disabled="isAdding" :loading="isAdding">{{ isAdding? 'Editing ...': 'Edit user'}}</Button>  
                    </div>
                </Modal>

                <!-- User delete modal -->
                <Modal v-model="deleteModal" width="360">
                    <p slot="header" style="color:#f60;text-align:center">
                        <Icon type="ios-information-circle"></Icon>
                        <span>Delete confirmation</span>
                    </p>
                    <div style="text-align:center">
                        <p>Are you sure to delete this user?</p>
                    </div>
                    <div slot="footer">
                        <Button type="error" size="large" long :disabled="isDeleting" :loading="isDeleting" @click="deleteUser">{{ isDeleting? 'Deleting ...': 'Delete user'}}</Button>
                    </div>
                </Modal>

			</div>
		</div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            data: {
                username: '',
                firstname: '',
                lastname: '',
                email: '',
                password: ''
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            users: [],
            selectedUser: {
                id: -1,
                username: '',
                firstname: '',
                lastname: '',
                email: ''
            },
            index: -1,
            isDeleting: false,
            deleteModal: false,
            searchData: {
                username: '',
                firstname: '',
                lastname: '',
            },
            total: 0,
            page: 1,
            pageSize: 10,
        }
    },
    beforeCreate() {

    },
    async created() {
        axios({
            method: 'GET',
            url: '/api/user/get/'+this.page+'/'+this.pageSize
        })
        .then(res => {
            if (res.status == 200) {
                this.users = res.data.users
                this.total = res.data.total
            } else {
                this.swr('')
            }
        })
        .catch(error => {
            console.log('error::', error);
        })
    },
    mounted() {

    },
    methods: {
        async addUser() {
            this.isAdding = true
            const res = await this.callApi('post', '/api/user/create', this.data)
            if (res.status == 201) {
                this.users.unshift(res.data)
                this.s('User has been registered successfully')
                this.addModal = false
                this.isAdding = false
                this.data = {
                    username: '',
                    firstname: '',
                    lastname: '',
                    email: '',
                    password: ''
                }
            } else {
                if (res.status == 401) {
                    this.isAdding = false
                    let errors = res.data.errors
                    if (errors.username)  return this.i(errors.username)
                    if (errors.firstname) return this.i(errors.firstname)
                    if (errors.lastname)  return this.i(errors.lastname)
                    if (errors.email)     return this.i(errors.email)
                    if (errors.password)  return this.i(errors.password)
                }
            }
        },
        async editUser() {
            this.isAdding = true
            const res = await this.callApi('post', '/api/user/edit', this.selectedUser)
            if (res.status == 200) {
                this.s('The user has been updated successfully')
                this.editModal = false
                this.isAdding = false
            } else {
                if (res.status == 401) {
                    this.isAdding = false
                    let errors = res.data.errors
                    if (errors.username)  return this.i(errors.username)
                    if (errors.firstname) return this.i(errors.firstname)
                    if (errors.lastname)  return this.i(errors.lastname)
                    if (errors.email)     return this.i(errors.email)
                }
            }
        },
        showEditModal(user) {
            this.selectedUser = user
            this.editModal = true
        },
        async deleteUser() {
            this.isDeleting = true
            const res = await this.callApi('post', '/api/user/delete', this.selectedUser)
            if (res.status == 200) {
                this.users.splice(this.i, 1);
                this.deleteModal = false
                this.isDeleting = false
                this.s('The user has been deleted successfully')
            } else {
                this.swr('')
            }
        },
        showDeleteModal(user, index) {
            this.index = index
            this.deleteModal = true
            this.selectedUser = user
        },
        handlePage(p) {
            this.page = p
            axios({
                method: 'GET',
                url: '/api/user/get/'+this.page+'/'+this.pageSize
            })
            .then(res => {
                if (res.status == 200) {
                    this.users = res.data.users
                } else {
                    this.swr('')
                }
            })
            .catch(error => {
                console.log('error::', error);
            })
        }
    },
    watch: {
        searchData: {
            async handler(val) {
                const res = await this.callApi('post', '/api/user/search', val)
                if (res.status == 200) {
                    this.total = res.data.total
                    this.users = res.data.users
                }
            },
            deep: true
        }
    }
}
</script>