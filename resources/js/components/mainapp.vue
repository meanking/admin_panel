<template>
  <div>
    <!--========== ADMIN SIDE MENU one ========-->
    <div class="_1side_menu">
      <div class="_1side_menu_logo">
        <h3 style="text-align:center;">Admin Panel</h3>
      </div>

      <!--~~~~~~~~ MENU CONTENT ~~~~~~~~-->
      <div class="_1side_menu_content">
        <div class="_1side_menu_img_name">
          <p class="_1side_menu_name"></p>
        </div>

        <!--~~~ MENU LIST ~~~~~~-->
        <Menu :active-name="$route.name" :open-names="openNames" width="auto" @on-select="select">
          <MenuItem name="dashboard">
            <Icon type="ios-analytics" />Dashboard
          </MenuItem>
          <MenuItem name="employee-management">
            <Icon type="md-person" />Employee Management
          </MenuItem>
          <Submenu name="system-management">
            <template slot="title">
              <Icon type="md-settings" />System Mangement
            </template>
            <MenuItem name="department">
              <Icon type="ios-paper" />Department
            </MenuItem>
            <MenuItem name="division">
              <Icon type="md-paper" />Division
            </MenuItem>
            <MenuItem name="country">
              <Icon type="ios-planet" />Country
            </MenuItem>
            <MenuItem name="state">
              <Icon type="ios-pin" />State
            </MenuItem>
            <MenuItem name="city">
              <Icon type="ios-pin-outline" />City
            </MenuItem>
          </Submenu>
          <MenuItem name="user">
            <Icon type="ios-person" />User
          </MenuItem>
        </Menu>
      </div>
    </div>
    <!--========== ADMIN SIDE MENU ========-->

    <!--========= HEADER ==========-->
    <div class="header">
      <div class="_2menu _box_shadow">
        <div class="_2menu_logo">
          <ul class="open_button">
            <li>
              <Icon type="ios-list" />
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!--========= HEADER ==========-->
    <router-view />
  </div>
</template>

<script>
export default {
  data() {
    return {
      openNames: [],
    };
  },
  created() {
    let names = ['department', 'division', 'country', 'state', 'city'];
    if (names.includes(this.$route.name)) {
      this.openNames.push('system-management')
    }
  },
  methods: {
    select(name) {
      if (name != this.$route.name) {
        this.$router.push({ name });
      }
    },
  }
};
</script>
