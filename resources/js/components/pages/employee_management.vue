<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Employees <Button @click="addModal=true"><Icon type="md-add" /> Add employee</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Full name</th>
								<th>Address</th>
								<th>Zip</th>
								<th>Age</th>
								<th>Department</th>
								<th>Division</th>
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
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
								<td>
                                    <Select prefix="ios-search" v-model="searchData.department_id" placeholder="Search by department" style="width:200px">
                                        <Option value="">Select department</Option>
                                        <Option v-for="(department, i) in departments" :value="department.id" :key="i">{{ department.name }}</Option>
                                    </Select>
                                </td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
								<!-- FILTER -->
								<!-- ITEMS -->
							<tr v-for="(employee, i) in employees" :key="i">
								<td>{{employee.id}}</td>
								<td>{{employee.firstname}} {{employee.middlename? employee.middlename + ' ': ''}}{{employee.lastname}}</td>
								<td>{{employee.address}}, {{employee.city.name}}, {{employee.state.name}}, {{employee.country.name}}</td>
								<td>{{employee.zip}}</td>
								<td>{{employee.age}}</td>
								<td>{{employee.department.name}}</td>
								<td>{{employee.division.name}}</td>
								<td>
									<Button type="info" @click="showEditModal(employee)">Edit</Button>
                                    <Button type="error" @click="showDeleteModal(employee, i)">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->


						</table>
					</div>
				</div>

                <!-- Employee adding modal -->
                <Modal
                    v-model="addModal"
                    title="Add employee"
                    :mask-closable="false"
                    :closable="false"
                    >
                    <row>
                        <i-col span="12">
                            <Input v-model="data.firstname" placeholder="First name" maxlength="60" show-word-limit style="width:200px" />
                        </i-col>
                        <i-col span="12">
                            <Input v-model="data.middlename" placeholder="Middle name" maxlength="60" show-word-limit style="width:200px" />
                        </i-col>
                    </row>
                    <row style="margin-top: 15px;">
                        <i-col span="12">
                            <Input v-model="data.lastname" placeholder="Last name" maxlength="60" show-word-limit style="width:200px" />
                        </i-col>
                    </row>
                    <row style="margin-top: 15px;">
                        <i-col span="24">
                            <Input v-model="data.address" placeholder="Address" maxlength="120" show-word-limit />
                        </i-col>
                    </row>
                    <row style="margin-top: 15px;">
                        <i-col span="12">
                            <Select v-model="data.country_id" placeholder="Select country" style="width:200px">
                                <Option v-for="(country, i) in countries" :value="country.id" :key="i">{{ country.name }}</Option>
                            </Select>
                        </i-col>
                        <i-col span="12">
                            <Select v-model="data.state_id" placeholder="Select state" style="width:200px">
                                <Option v-for="(state, i) in states" :value="state.id" :key="i">{{ state.name }}</Option>
                            </Select>
                        </i-col>
                    </row>
                    <row style="margin-top: 15px;">
                        <i-col span="12">
                            <Select v-model="data.city_id" placeholder="Select city" style="width:200px">
                                <Option v-for="(city, i) in cities" :value="city.id" :key="i">{{ city.name }}</Option>
                            </Select>
                        </i-col>
                    </row>
                    <row style="margin-top: 15px;">
                        <i-col span="12">
                            <Input v-model="data.zip" placeholder="Zip" maxlength="10" show-word-limit style="width:200px" />
                        </i-col>
                        <i-col span="12">
                            <Input v-model="data.age" placeholder="Age" style="width:200px" />
                        </i-col>
                    </row>
                    <row style="margin-top: 15px;">
                        <i-col span="12">
                            <DatePicker type="date" format="yyyy-MM-dd" v-model="data.birthdate" placeholder="Select birthdate" style="width: 200px"></DatePicker>
                        </i-col>
                        <i-col span="12">
                            <DatePicker type="date" format="yyyy-MM-dd" v-model="data.date_hired" placeholder="Select hired date" style="width: 200px"></DatePicker>
                        </i-col>
                    </row>
                    <row style="margin-top: 15px;">
                        <i-col span="12">
                            <Select v-model="data.department_id" placeholder="Select department" style="width:200px">
                                <Option v-for="(department, i) in departments" :value="department.id" :key="i">{{ department.name }}</Option>
                            </Select>
                        </i-col>
                        <i-col span="12">
                            <Select v-model="data.division_id" placeholder="Select division" style="width:200px">
                                <Option v-for="(division, i) in divisions" :value="division.id" :key="i">{{ division.name }}</Option>
                            </Select>
                        </i-col>
                    </row>
                    <div style="margin-top: 15px;">
                        <Upload
                            :before-upload="handleUpload"
                            action="">
                            <Button icon="ios-cloud-upload-outline">Select image</Button>
                        </Upload>
                        <div v-if="data.picture !== null">Upload file: {{ data.picture.name }} </div>
                    </div>
                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Cancel</Button>
                        <Button type="primary" @click="addEmployee" :disabled="isAdding" :loading="isAdding">{{ isAdding? 'Adding ...': 'Add employee'}}</Button>  
                    </div>
                </Modal>

                <!-- Employee edit modal -->
                <Modal
                    v-model="editModal"
                    title="Edit employee"
                    :mask-closable="false"
                    :closable="false"
                    >
                    <row>
                        <i-col span="12">
                            <Input v-model="selectedObj.firstname" placeholder="First name" maxlength="60" show-word-limit style="width:200px" />
                        </i-col>
                        <i-col span="12">
                            <Input v-model="selectedObj.middlename" placeholder="Middle name" maxlength="60" show-word-limit style="width:200px" />
                        </i-col>
                    </row>
                    <row style="margin-top: 15px;">
                        <i-col span="12">
                            <Input v-model="selectedObj.lastname" placeholder="Last name" maxlength="60" show-word-limit style="width:200px" />
                        </i-col>
                    </row>
                    <row style="margin-top: 15px;">
                        <i-col span="24">
                            <Input v-model="selectedObj.address" placeholder="Address" maxlength="120" show-word-limit />
                        </i-col>
                    </row>
                    <row style="margin-top: 15px;">
                        <i-col span="12">
                            <Select v-model="selectedObj.country_id" placeholder="Select country" style="width:200px">
                                <Option v-for="(country, i) in countries" :value="country.id" :key="i">{{ country.name }}</Option>
                            </Select>
                        </i-col>
                        <i-col span="12">
                            <Select v-model="selectedObj.state_id" placeholder="Select state" style="width:200px">
                                <Option v-for="(state, i) in states" :value="state.id" :key="i">{{ state.name }}</Option>
                            </Select>
                        </i-col>
                    </row>
                    <row style="margin-top: 15px;">
                        <i-col span="12">
                            <Select v-model="selectedObj.city_id" placeholder="Select city" style="width:200px">
                                <Option v-for="(city, i) in cities" :value="city.id" :key="i">{{ city.name }}</Option>
                            </Select>
                        </i-col>
                    </row>
                    <row style="margin-top: 15px;">
                        <i-col span="12">
                            <Input v-model="selectedObj.zip" placeholder="Zip" maxlength="10" show-word-limit style="width:200px" />
                        </i-col>
                        <i-col span="12">
                            <Input v-model="selectedObj.age" placeholder="Age" style="width:200px" />
                        </i-col>
                    </row>
                    <row style="margin-top: 15px;">
                        <i-col span="12">
                            <DatePicker type="date" format="yyyy-MM-dd" v-model="selectedObj.birthdate" placeholder="Select birthdate" style="width: 200px"></DatePicker>
                        </i-col>
                        <i-col span="12">
                            <DatePicker type="date" format="yyyy-MM-dd" v-model="selectedObj.date_hired" placeholder="Select hired date" style="width: 200px"></DatePicker>
                        </i-col>
                    </row>
                    <row style="margin-top: 15px;">
                        <i-col span="12">
                            <Select v-model="selectedObj.department_id" placeholder="Select department" style="width:200px">
                                <Option v-for="(department, i) in departments" :value="department.id" :key="i">{{ department.name }}</Option>
                            </Select>
                        </i-col>
                        <i-col span="12">
                            <Select v-model="selectedObj.division_id" placeholder="Select division" style="width:200px">
                                <Option v-for="(division, i) in divisions" :value="division.id" :key="i">{{ division.name }}</Option>
                            </Select>
                        </i-col>
                    </row>
                    <div style="margin-top: 15px;">
                        <Upload
                            :before-upload="selectedObjHandleUpload"
                            action="">
                            <Button icon="ios-cloud-upload-outline">Select image</Button>
                        </Upload>
                        <div v-if="selectedObj.picture !== null">Upload file: {{ selectedObj.picture.name }} </div>
                    </div>
                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Cancel</Button>
                        <Button type="primary" @click="editEmployee" :disabled="isAdding" :loading="isAdding">{{ isAdding? 'Editing ...': 'Edit employee'}}</Button>  
                    </div>
                </Modal>

                <!-- Employee delete modal -->
                <Modal v-model="deleteModal" width="360">
                    <p slot="header" style="color:#f60;text-align:center">
                        <Icon type="ios-information-circle"></Icon>
                        <span>Delete confirmation</span>
                    </p>
                    <div style="text-align:center">
                        <p>Are you sure to delete this employee?</p>
                    </div>
                    <div slot="footer">
                        <Button type="error" size="large" long :disabled="isDeleting" :loading="isDeleting" @click="deleteEmployee">{{ isDeleting? 'Deleting ...': 'Delete employee'}}</Button>
                    </div>
                </Modal>

			</div>
		</div>
    </div>
</template>

<script>
import moment from 'moment'

export default {
    data() {
        return {
            data: {
                firstname: '',
                middlename: '',
                lastname: '',
                address: '',
                city_id: '',
                state_id: '',
                country_id: '',
                zip: '',
                age: '',
                birthdate: '',
                date_hired: '',
                department_id: '',
                division_id: '',
                picture: null,
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            employees: [],
            countries: [],
            states: [],
            cities: [],
            departments: [],
            divisions: [],
            selectedObj: {
                id: -1,
                firstname: '',
                middlename: '',
                lastname: '',
                address: '',
                city_id: '',
                state_id: '',
                country_id: '',
                zip: '',
                age: '',
                birthdate: '',
                date_hired: '',
                department_id: '',
                division_id: '',
                picture: null,
            },
            index: -1,
            isDeleting: false,
            deleteModal: false,
            searchData: {
                fullname: '',
                department_id: '',
            }
        }
    },
    beforeCreate() {

    },
    async created() {
        const res_employees   = await this.callApi('get', '/api/employee/get')
        const res_countries   = await this.callApi('get', '/api/country/get')
        const res_departments = await this.callApi('get', '/api/department/get')
        const res_divisions   = await this.callApi('get', '/api/division/get')
        if (res_employees.status == 200) {
            this.employees = res_employees.data
        } else {
            this.swr('')
        }
        if (res_countries.status == 200) {
            this.countries = res_countries.data
        } else {
            this.swr('')
        }
        if (res_departments.status == 200) {
            this.departments = res_departments.data
        } else {
            this.swr('')
        }
        if (res_divisions.status == 200) {
            this.divisions = res_divisions.data
        } else {
            this.swr('')
        }
    },
    mounted() {

    },
    methods: {
        async addEmployee() {
            this.isAdding = true

            let formData = new FormData();
            formData.append('firstname', this.data.firstname);
            formData.append('middlename', this.data.middlename);
            formData.append('lastname', this.data.lastname);
            formData.append('address', this.data.address);
            formData.append('city_id', this.data.city_id);
            formData.append('state_id', this.data.state_id);
            formData.append('country_id', this.data.country_id);
            formData.append('zip', this.data.zip);
            formData.append('age', this.data.age);
            formData.append('birthdate', moment(this.data.birthdate).format('YYYY-MM-DD'));
            formData.append('date_hired', moment(this.data.date_hired).format('YYYY-MM-DD'));
            formData.append('department_id', this.data.department_id);
            formData.append('division_id', this.data.division_id);
            formData.append('picture', this.data.picture);

            axios({
                method: 'POST',
                url: '/api/employee/create',
                data: formData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(res => {
                if (res.status == 200) {
                    this.employees.unshift(res.data)
                    this.s('Employee was registered successfully')
                    this.addModal = false
                    this.isAdding = false
                    this.data = {
                        firstname: '',
                        middlename: '',
                        lastname: '',
                        address: '',
                        city_id: '',
                        state_id: '',
                        country_id: '',
                        zip: '',
                        age: '',
                        birthdate: '',
                        date_hired: '',
                        department_id: '',
                        division_id: '',
                        picture: null,
                    }
                } else {
                    if (res.status == 401) {
                        this.isAdding = false
                        let errors = res.data.errors
                        if (errors.firstname)     return this.i(errors.firstname)
                        if (errors.lastname)      return this.i(errors.lastname)
                        if (errors.address)       return this.i(errors.address)
                        if (errors.country_id)    return this.i(errors.country_id)
                        if (errors.state_id)      return this.i(errors.state_id)
                        if (errors.city_id)       return this.i(errors.city_id)
                        if (errors.zip)           return this.i(errors.zip)
                        if (errors.age)           return this.i(errors.age)
                        if (errors.birthdate)     return this.i(errors.birthdate)
                        if (errors.date_hired)    return this.i(errors.date_hired)
                        if (errors.department_id) return this.i(errors.department_id)
                        if (errors.division_id)   return this.i(errors.division_id)
                    }
                }
            })
            .catch(error => {
                this.isAdding = false
                console.log('error::', error);
            })
        },
        async editEmployee() {
            this.isAdding = true

            let formData = new FormData();
            formData.append('id', this.selectedObj.id);
            formData.append('firstname', this.selectedObj.firstname);
            formData.append('middlename', this.selectedObj.middlename);
            formData.append('lastname', this.selectedObj.lastname);
            formData.append('address', this.selectedObj.address);
            formData.append('city_id', this.selectedObj.city_id);
            formData.append('state_id', this.selectedObj.state_id);
            formData.append('country_id', this.selectedObj.country_id);
            formData.append('zip', this.selectedObj.zip);
            formData.append('age', this.selectedObj.age);
            formData.append('birthdate', moment(this.selectedObj.birthdate).format('YYYY-MM-DD'));
            formData.append('date_hired', moment(this.selectedObj.date_hired).format('YYYY-MM-DD'));
            formData.append('department_id', this.selectedObj.department_id);
            formData.append('division_id', this.selectedObj.division_id);
            formData.append('picture', this.selectedObj.picture);

            axios({
                method: 'POST',
                url: '/api/employee/edit',
                data: formData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(res => {
                if (res.status == 200) {
                    this.s('Employee was updated successfully')
                    this.editModal = false
                    this.isAdding = false
                } else {
                    if (res.status == 401) {
                        this.isAdding = false
                        let errors = res.data.errors
                        if (errors.firstname)     return this.i(errors.firstname)
                        if (errors.lastname)      return this.i(errors.lastname)
                        if (errors.address)       return this.i(errors.address)
                        if (errors.country_id)    return this.i(errors.country_id)
                        if (errors.state_id)      return this.i(errors.state_id)
                        if (errors.city_id)       return this.i(errors.city_id)
                        if (errors.zip)           return this.i(errors.zip)
                        if (errors.age)           return this.i(errors.age)
                        if (errors.birthdate)     return this.i(errors.birthdate)
                        if (errors.date_hired)    return this.i(errors.date_hired)
                        if (errors.department_id) return this.i(errors.department_id)
                        if (errors.division_id)   return this.i(errors.division_id)
                    }
                }
            })
            .catch(error => {
                this.isAdding = false
                console.log('error::', error);
            })
        },
        async showEditModal(employee) {
            const res_states = await this.callApi('post', '/api/country/getById', {country_id: employee.country_id})
            if (res_states.status == 200) {
                this.states = res_states.data.states
            }
            const res_cities = await this.callApi('post', '/api/state/getById', {state_id: employee.state_id})
            if (res_cities.status == 200) {
                this.cities = res_cities.data.cities
            }
            this.selectedObj = employee
            this.selectedObj.middlename = employee.middlename == null || employee.middlename == 'null'? '':employee.middlename
            this.editModal   = true
        },
        async deleteEmployee(employee) {
            this.isDeleting = true
            const res = await this.callApi('post', '/api/employee/delete', this.selectedObj)
            if (res.status == 200) {
                this.employees.splice(this.i, 1);
                this.deleteModal = false
                this.isDeleting = false
                this.s('The employee was deleted successfully')
            } else {
                this.swr('')
            }
        },
        showDeleteModal(employee, index) {
            this.index = index
            this.deleteModal = true
            this.selectedObj = employee
        },
        handleUpload (file) {
            this.data.picture = file;
        },
        selectedObjHandleUpload (file) {
            this.selectedObj.picture = file;
        },
    },
    watch: {
        searchData: {
            async handler(val) {
                const res = await this.callApi('post', '/api/employee/search', val)
                if (res.status == 200) {
                    this.employees = res.data
                }
            },
            deep: true
        },        
        'data.country_id': {
            async handler(val) {
                const res = await this.callApi('post', '/api/country/getById', {country_id: val})
                if (res.status == 200) {
                    this.states = res.data.states
                }
            },
            deep: true
        },
        'data.state_id': {
            async handler(val) {
                const res = await this.callApi('post', '/api/state/getById', {state_id: val})
                if (res.status == 200) {
                    this.cities = res.data.cities
                }
            },
            deep: true
        },
        'selectedObj.country_id': {
            async handler(val) {
                const res = await this.callApi('post', '/api/country/getById', {country_id: val})
                if (res.status == 200) {
                    this.states                   = res.data.states
                    this.selectedObj.country.name = res.data.name
                }
            },
            deep: true
        },
        'selectedObj.state_id': {
            async handler(val) {
                const res = await this.callApi('post', '/api/state/getById', {state_id: val})
                if (res.status == 200) {
                    this.cities                 = res.data.cities
                    this.selectedObj.state.name = res.data.name
                }
            },
            deep: true
        },
        'selectedObj.city_id': {
            async handler(val) {
                const res = await this.callApi('post', '/api/city/getById', {city_id: val})
                if (res.status == 200) {
                    this.selectedObj.city.name = res.data.name
                }
            },
            deep: true
        }
    }
}
</script>