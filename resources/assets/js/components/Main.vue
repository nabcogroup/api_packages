<template>
<div id="admin-panel" :class="{'nav-close': isNavClose}">
    <v-sidebar :menus="menus" :logo="logo" v-show="!isNavClose"></v-sidebar>
    <v-navbar nav-class="navbar-static-top" v-model="isNavClose" :logo="logo">
        <template slot="logging">
            <li>
                <a href="#">
                    <i class="fa fa-bell"></i>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-envelope-open"></i>
                </a>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user-circle fa-lg" aria-hidden="true"></i> {{user.name}}
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#" @click.prevent="onLoggingClick('logout')">Log-out</a></li>
                </ul>
            </li>   
        </template>
    </v-navbar>
    <div class="container-fluid">
        <main style="padding: 0 15px;">
            <router-view></router-view>
        </main>   
    </div>
</div>
  
</template>


<script>
export default {
  data() {
    return {
      isNavClose: false,
      user: {
        name: "admin"
      },
      menus: {
        control: {
          icon: "fa-certificate",
          name: "Control",
          visible: true,
          submenus: [
            {
              disabled: false,
              icon: "fa-certificate",
              name: "Profile",
              url: "/profile"
            },
            {
              disabled: false,
              icon: "fa-certificate",
              name: "Form",
              url: "/form"
            },
            {
              disabled: false,
              icon: "fa-certificate",
              name: "Tab",
              url: "/tab"
            },
            {
              disabled: false,
              icon: "fa-certificate",
              name: "Grid",
              url: "/grid"
            }
          ]
        }
      },
      logo: {
        url: "http://localhost:8081",
        imgPath: "http://localhost:8081/imgs/logo.png",
        title: "Admin Panel"
      }
    };
  },
  methods: {
    onLoggingClick() {}
  }
};
</script>