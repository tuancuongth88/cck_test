<template>
  <!-- <div v-for="(itemRouter, indexRouter) in router" :key="indexRouter">
      {{ itemRouter.path + '==> ' + itemRouter.name }}
      <br>
    </div> -->

  <ul class="display-menu">
    <template v-for="(itemRouter, indexRouter) in router">
      <template v-if="itemRouter.meta.role.includes(permissionCheck)">
        <!-- Sysadmin -->
        <template v-if="permissionCheck == ROLE.TYPE_SUPER_ADMIN">
          <!-- Check dropdown tab -->
          <li
            v-if="
              itemRouter.name === 'HrOrganization' ||
                itemRouter.name === 'CompanyManagement'
            "
            :key="indexRouter"
          >
            <a
              href="#"
              class="link-dropdown"
              :class="[
                itemRouter.name === 'CompanyManagement' ? 'dusk-company' : '',
              ]"
            >
              <span>{{ $t(itemRouter.meta.title) }}</span>
              <ul class="dropdown-list">
                <li class="dropdown-item" @click="handleClickToNewTab">
                  <router-link
                    :to="
                      itemRouter.name === 'HrOrganization'
                        ? '/hr-organization'
                        : '/company'
                    "
                  >
                    {{
                      itemRouter.name === 'HrOrganization'
                        ? $t('ROUTER_HR_ORGANIZATION_LIST')
                        : $t('TAB_COMPANY_LIST')
                    }}
                  </router-link>
                </li>
                <li class="dropdown-item" @click="handleClickToNewTab">
                  <router-link
                    :to="itemRouter.name === 'HrOrganization' ? '/hr' : '/job'"
                    :class="[
                      itemRouter.name === 'HrOrganization'
                        ? 'dusk-hr'
                        : 'dusk-job',
                    ]"
                  >
                    {{
                      itemRouter.name === 'HrOrganization'
                        ? $t('ROUTER_HR_LIST')
                        : $t('ROUTER_JOB_OFFER_LIST_COMPANY')
                    }}
                  </router-link>
                </li>

                <li
                  v-if="itemRouter.name === 'CompanyManagement'"
                  class="dropdown-item"
                  @click="handleClickToNewTab"
                >
                  <router-link
                    :to="
                      itemRouter.name === 'CompanyManagement'
                        ? '/job-search/list'
                        : ''
                    "
                  >
                    {{ $t('ROUTER_JOB_OFFER_LIST_HR') }}
                  </router-link>
                </li>
              </ul>
            </a>
          </li>
          <li
            v-else-if="
              itemRouter.name === 'HomeManagement' ||
                itemRouter.name === 'MatchingManagement'
            "
            :key="indexRouter"
            @click="handleClickToNewTab"
          >
            <router-link :key="indexRouter" :to="itemRouter.path">
              {{ $t(itemRouter.meta.title) }}
            </router-link>
          </li>
        </template>

        <!-- Company manager -->
        <template v-if="permissionCheck == ROLE.TYPE_COMPANY_ADMIN">
          <!-- Check dropdown tab -->
          <li v-if="itemRouter.name === 'CompanyManagement'" :key="indexRouter">
            <a href="#" class="link-dropdown">
              <span
                :class="[
                  itemRouter.name === 'CompanyManagement' ? 'dusk-company' : '',
                ]"
              >{{ $t(itemRouter.meta.title) }}
              </span>
              <ul class="dropdown-list">
                <li class="dropdown-item">
                  <router-link :to="'/company'" @click="handleClickToNewTab">
                    {{ $t('TAB_COMPANY_LIST') }}
                  </router-link>
                </li>
                <li class="dropdown-item">
                  <router-link
                    :to="'/job'"
                    class="dusk-job"
                    @click="handleClickToNewTab"
                  >
                    {{ $t('TAB_JOB_LIST') }}
                  </router-link>
                </li>
              </ul>
            </a>
          </li>
          <li
            v-else-if="
              itemRouter.name === 'HomeManagement' ||
                itemRouter.name === 'MatchingManagement'
            "
            :key="indexRouter"
            @click="handleClickToNewTab"
          >
            <router-link :key="indexRouter" :to="itemRouter.path">
              {{ $t(itemRouter.meta.title) }}
            </router-link>
          </li>
          <li
            v-else-if="itemRouter.name === 'HrSearch'"
            :key="indexRouter"
            @click="handleClickToNewTab"
          >
            <router-link :key="indexRouter" :to="itemRouter.path">
              {{ $t(itemRouter.meta.title) }}
            </router-link>
          </li>
        </template>

        <!-- Hr manager -->
        <template v-if="permissionCheck == ROLE.TYPE_HR_MANAGER">
          <!-- Check dropdown tab -->
          <li v-if="itemRouter.name === 'HrOrganization'" :key="indexRouter">
            <a href="#" class="link-dropdown">
              <span>{{ $t(itemRouter.meta.title) }}</span>
              <ul class="dropdown-list">
                <li class="dropdown-item" @click="handleClickToNewTab">
                  <router-link :to="'/hr-organization'">
                    {{ $t('ROUTER_HR_ORGANIZATION_LIST') }}
                  </router-link>
                </li>
                <li class="dropdown-item" @click="handleClickToNewTab">
                  <router-link :to="'/hr'">
                    {{ $t('ROUTER_HR_LIST') }}
                  </router-link>
                </li>
              </ul>
            </a>
          </li>
          <li
            v-else-if="
              itemRouter.name === 'HomeManagement' ||
                itemRouter.name === 'MatchingManagement'
            "
            :key="indexRouter"
            @click="handleClickToNewTab"
          >
            <router-link :key="indexRouter" :to="itemRouter.path">
              {{ $t(itemRouter.meta.title) }}
            </router-link>
          </li>
          <li
            v-else-if="itemRouter.name === 'JobSearch'"
            :key="indexRouter"
            :class="[itemRouter.name === 'JobSearch' ? 'dusk-company' : '']"
            @click="handleClickToNewTab"
          >
            <router-link :key="indexRouter" :to="itemRouter.path">
              {{ $t(itemRouter.meta.title) }}
            </router-link>
          </li>
        </template>

        <!-- Company -->
        <template v-if="permissionCheck == ROLE.TYPE_COMPANY">
          <li :key="indexRouter" @click="handleClickToNewTab">
            <router-link
              :key="indexRouter"
              :to="itemRouter.path"
              :class="[itemRouter.name === 'JobManagement' ? 'dusk-job' : '']"
            >
              {{ $t(itemRouter.meta.title) }}
            </router-link>
          </li>
        </template>

        <!-- HR -->
        <template v-if="permissionCheck == ROLE.TYPE_HR">
          <li :key="indexRouter" @click="handleClickToNewTab">
            <router-link
              :key="indexRouter"
              :to="itemRouter.path"
              :class="[itemRouter.name === 'JobSearch' ? 'dusk-company' : '']"
            >
              {{ $t(itemRouter.meta.title) }}
            </router-link>
          </li>
        </template>
      </template>
    </template>
  </ul>
</template>

<script>
import ROLE from '@/const/role.js';
export default {
  name: 'Menu',
  data() {
    return {
      ROLE: ROLE,
    };
  },
  computed: {
    router() {
      return this.$store.getters.permissionRoutes.filter(
        (item) => item.hidden !== true
      );
    },

    // router() {
    //   return this.$store.getters.permissionRoutes;
    // },

    permissionCheck() {
      return this.$store.getters.permissionCheck;
    },

    // role() {
    //   return this.$store.getters.role.filter((item) => item.hidden !== true);
    // },

    // permissionRoutes() {
    //   return this.$store.getters.permissionRoutes.filter(
    //     (item) => item.hidden !== true
    //   );
    // },
  },

  methods: {
    async handleClickToNewTab() {
      await this.$store.dispatch('hrSearchQuery/setSearchParams', null);
      await this.$store.dispatch('jobSearch/setJobSearchData', null);
      this.$store.dispatch('app/setRedirectToModalSelectJobsToOffer', false);
    },
  },
};
</script>

<style lang="scss" scoped>
@import '../../scss/_variables.scss';
.display-menu {
  list-style: none;
  padding: 0;
  display: flex;
  align-items: center;
  width: 100%;
  margin-bottom: 0;
  > li {
    flex: 1;
    &:first-child {
      > a {
        border-radius: 20px 0px 0px;
      }
    }
    &:last-child {
      > a {
        border-radius: 0px 20px 0px 0px;
        border-right: 0;
      }
    }
    > a {
      display: block;
      text-align: center;
      height: 50px;
      background-color: #1d266a;
      border-right: 1px solid #fff;
      line-height: 50px;
      color: #fff;
      font-weight: 700;
      font-size: 16px;
      &:hover,
      &.router-link-active {
        text-decoration: unset;
        background-color: #fff;
        color: #1d266a;
        box-shadow: 0px -2px 0px 0px #1d266a inset;
      }
      > div {
        background-color: transparent;
      }
      &.link-dropdown {
        position: relative;
        ul.dropdown-list {
          padding: 0;
          list-style: none;
          background-color: #fff;
          position: absolute;
          top: 50px;
          width: 100%;
          visibility: hidden;
          transition: 0.2s;
          .dropdown-item {
            padding: 0;
            a {
              display: block;
              height: 50px;
              line-height: 50px;
              background-color: #f5faff;
              color: #1d266a;
              font-size: 16px;
              font-weight: 700;
              &:hover,
              &.router-link-active {
                background-color: #1d266a;
                color: #fff;
                text-decoration: unset;
              }
            }
          }
        }
        &:hover {
          ul.dropdown-list {
            visibility: visible;
            z-index: 99;
          }
        }
      }
    }
  }
}
</style>
