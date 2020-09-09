<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Divisions <Button @click="addModal=true"><Icon type="md-add" /> Add division</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->                                
								<!-- FILTER -->
							<tr>
								<td>&nbsp;</td>
								<td>
                                    <Input suffix="ios-search" v-model="searchData.name" placeholder="Search by name" style="width: auto" />
                                </td>
								<td>&nbsp;</td>
							</tr>
								<!-- FILTER -->
								<!-- ITEMS -->
							<tr v-for="(division, i) in divisions" :key="i">
								<td>{{division.id}}</td>
								<td>{{division.name}}</td>
								<td>
									<Button type="info" @click="showEditModal(division)">Edit</Button>
                                    <Button type="error" @click="showDeleteModal(division, i)">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->


						</table>
					</div>
				</div>
                <Page :total="total" :current="page" @on-change="handlePage" />

                <!-- Division adding modal -->
                <Modal
                    v-model="addModal"
                    title="Add division"
                    :mask-closable="false"
                    :closable="false"
                    >
                    
                    <Input v-model="data.name" placeholder="Division" maxlength="60" show-word-limit />
                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Cancel</Button>
                        <Button type="primary" @click="addDivision" :disabled="isAdding" :loading="isAdding">{{ isAdding? 'Adding ...': 'Add division'}}</Button>  
                    </div>
                </Modal>

                <!-- Division edit modal -->
                <Modal
                    v-model="editModal"
                    title="Edit division"
                    :mask-closable="false"
                    :closable="false"
                    >
                    
                    <Input v-model="selectedObj.name" maxlength="60" show-word-limit />
                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Cancel</Button>
                        <Button type="primary" @click="editDivision" :disabled="isAdding" :loading="isAdding">{{ isAdding? 'Editing ...': 'Edit division'}}</Button>  
                    </div>
                </Modal>

                <!-- Division delete modal -->
                <Modal v-model="deleteModal" width="360">
                    <p slot="header" style="color:#f60;text-align:center">
                        <Icon type="ios-information-circle"></Icon>
                        <span>Delete confirmation</span>
                    </p>
                    <div style="text-align:center">
                        <p>Are you sure to delete this division?</p>
                    </div>
                    <div slot="footer">
                        <Button type="error" size="large" long :disabled="isDeleting" :loading="isDeleting" @click="deleteDivision">{{ isDeleting? 'Deleting ...': 'Delete division'}}</Button>
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
                name: ''
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            divisions: [],
            selectedObj: {
                id: -1,
                name: ''
            },
            index: -1,
            isDeleting: false,
            deleteModal: false,
            searchData: {
                name: ''
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
            url: '/api/division/get/'+this.page+'/'+this.pageSize
        })
        .then(res => {
            if (res.status == 200) {
                this.divisions = res.data.divisions
                this.total     = res.data.total
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
        async addDivision() {
            this.isAdding = true
            const res = await this.callApi('post', '/api/division/create', this.data)
            if (res.status == 201) {
                this.divisions.unshift(res.data)
                this.s('Division has been registered successfully')
                this.addModal = false
                this.isAdding = false
                this.data = {
                    name: ''
                }
            } else {
                if (res.status == 401) {
                    this.isAdding = false
                    let errors = res.data.errors
                    if (errors.name)  return this.i(errors.name)
                }
            }
        },
        async editDivision() {
            this.isAdding = true
            const res = await this.callApi('post', '/api/division/edit', this.selectedObj)
            if (res.status == 200) {
                this.s('The division has been updated successfully')
                this.editModal = false
                this.isAdding = false
            } else {
                if (res.status == 401) {
                    this.isAdding = false
                    let errors = res.data.errors
                    if (errors.name)  return this.i(errors.name)
                }
            }
        },
        showEditModal(division) {
            this.selectedObj = division
            this.editModal = true
        },
        async deleteDivision() {
            this.isDeleting = true
            const res = await this.callApi('post', '/api/division/delete', this.selectedObj)
            if (res.status == 200) {
                this.divisions.splice(this.index, 1);
                this.deleteModal = false
                this.isDeleting = false
                this.s('The division has been deleted successfully')
            } else {
                this.swr('')
            }
        },
        showDeleteModal(division, index) {
            this.index = index
            this.deleteModal = true
            this.selectedObj = division
        },
        handlePage(p) {
            this.page = p
            axios({
                method: 'GET',
                url: '/api/division/get/'+this.page+'/'+this.pageSize
            })
            .then(res => {
                if (res.status == 200) {
                    this.divisions = res.data.divisions
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
                const res = await this.callApi('post', '/api/division/search', val)
                if (res.status == 200) {
                    this.divisions = res.data
                }
            },
            deep: true
        }
    }
}
</script>