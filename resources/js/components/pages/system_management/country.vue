<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Counties <Button @click="addModal=true"><Icon type="md-add" /> Add country</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Code</th>
								<th>Name</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->                                
								<!-- FILTER -->
							<tr>
								<td>&nbsp;</td>
								<td>
                                    <Input suffix="ios-search" v-model="searchData.country_code" placeholder="Search by code" style="width: auto" />
                                </td>
								<td>
                                    <Input suffix="ios-search" v-model="searchData.name" placeholder="Search by name" style="width: auto" />
                                </td>
								<td>&nbsp;</td>
							</tr>
								<!-- FILTER -->
								<!-- ITEMS -->
							<tr v-for="(country, i) in countries" :key="i">
								<td>{{country.id}}</td>
								<td>{{country.country_code}}</td>
								<td>{{country.name}}</td>
								<td>
									<Button type="info" @click="showEditModal(country)">Edit</Button>
                                    <Button type="error" @click="showDeleteModal(country, i)">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->


						</table>
					</div>
				</div>
                <Page :total="total" :current="page" @on-change="handlePage" />

                <!-- Country adding modal -->
                <Modal
                    v-model="addModal"
                    title="Add country"
                    :mask-closable="false"
                    :closable="false"
                    >
                    
                    <Input v-model="data.country_code" placeholder="Country code" maxlength="3" show-word-limit />
                    <Input v-model="data.name" placeholder="Country" maxlength="60" show-word-limit style="margin-top: 15px;" />
                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Cancel</Button>
                        <Button type="primary" @click="addCountry" :disabled="isAdding" :loading="isAdding">{{ isAdding? 'Adding ...': 'Add country'}}</Button>  
                    </div>
                </Modal>

                <!-- Country edit modal -->
                <Modal
                    v-model="editModal"
                    title="Edit country"
                    :mask-closable="false"
                    :closable="false"
                    >
                    
                    <Input v-model="selectedObj.country_code" maxlength="3" show-word-limit />
                    <Input v-model="selectedObj.name" maxlength="60" show-word-limit style="margin-top: 15px;" />
                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Cancel</Button>
                        <Button type="primary" @click="editCountry" :disabled="isAdding" :loading="isAdding">{{ isAdding? 'Editing ...': 'Edit country'}}</Button>  
                    </div>
                </Modal>

                <!-- Country delete modal -->
                <Modal v-model="deleteModal" width="360">
                    <p slot="header" style="color:#f60;text-align:center">
                        <Icon type="ios-information-circle"></Icon>
                        <span>Delete confirmation</span>
                    </p>
                    <div style="text-align:center">
                        <p>Are you sure to delete this country?</p>
                    </div>
                    <div slot="footer">
                        <Button type="error" size="large" long :disabled="isDeleting" :loading="isDeleting" @click="deleteCountry">{{ isDeleting? 'Deleting ...': 'Delete country'}}</Button>
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
            countries: [],
            selectedObj: {
                id: -1,
                country_code: '',
                name: ''
            },
            index: -1,
            isDeleting: false,
            deleteModal: false,
            searchData: {
                country_code: '',
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
            url: '/api/country/get/'+this.page+'/'+this.pageSize
        })
        .then(res => {
            if (res.status == 200) {
                this.countries = res.data.countries
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
        async addCountry() {
            this.isAdding = true
            const res = await this.callApi('post', '/api/country/create', this.data)
            if (res.status == 201) {
                this.countries.unshift(res.data)
                this.s('Country has been registered successfully')
                this.addModal = false
                this.isAdding = false
                this.data = {
                    country_code: '',
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
        async editCountry() {
            this.isAdding = true
            const res = await this.callApi('post', '/api/country/edit', this.selectedObj)
            if (res.status == 200) {
                this.s('The country has been updated successfully')
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
        showEditModal(country) {
            this.selectedObj = country
            this.editModal = true
        },
        async deleteCountry() {
            this.isDeleting = true
            const res = await this.callApi('post', '/api/country/delete', this.selectedObj)
            if (res.status == 200) {
                this.countries.splice(this.index, 1);
                this.deleteModal = false
                this.isDeleting = false
                this.s('The country has been deleted successfully')
            } else {
                this.swr('')
            }
        },
        showDeleteModal(country, index) {
            this.index = index
            this.deleteModal = true
            this.selectedObj = country
        },
        handlePage(p) {
            this.page = p
            axios({
                method: 'GET',
                url: '/api/country/get/'+this.page+'/'+this.pageSize
            })
            .then(res => {
                if (res.status == 200) {
                    this.countries = res.data.countries
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
                const res = await this.callApi('post', '/api/country/search', val)
                if (res.status == 200) {
                    this.countries = res.data
                }
            },
            deep: true
        }
    }
}
</script>