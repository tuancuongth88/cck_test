<template>
  <div class="hr-basic-information">
    <div class="hr-basic-information-wrap">
      <div class="hr-basic-information-avata">
        <img
          :src="
            require('@/assets/images/login/hr-basic-info-avata-default.png')
          "
          alt="avata default"
        >
      </div>

      <div class="hr-basic-information-general">
        <div class="infor-first">
          <span class="info-full-name">{{ formData['full_name'] }}</span>
          <span class="info-full-name-ja">{{ formData['full_name_ja'] }}</span>
          <span class="info-id">{{ `(ID : ${hrIndentify})` }}</span>
        </div>

        <div class="infor-company mb-4">
          <span>{{ company_name_en }}</span>
          <span>{{ company_name_jp }}</span>
        </div>

        <div v-if="type === 'detail'">
          <div
            v-if="checkRoleFavourite"
            id="favorite"
            class="information-favorire-btns"
            @click="handleAddedToFavorites()"
          >
            <FavoriteBtn v-if="favorite_added" />
            <FavoriteBtnRemoved v-if="!favorite_added" />
          </div>

          <div
            v-if="checkRoleOffer"
            id="offer"
            class="btn information-detail-btns"
            :class="{ 'disable-offer': cv_status !== 1 }"
            @click="handleToggleModalSelectJobsToOffer()"
          >
            <span>オファーする</span>
          </div>
        </div>

        <div class="information-general-btns">
          <div
            :class="`btn ${
              cv_status === 1
                ? 'public-active'
                : `${cv_status === 3 ? 'disable-btn-offer' : 'public-deactive'}`
            }`"
          >
            <span>{{ $t('HR_LIST.STATUS_PUBLIC') }}</span>
          </div>

          <div
            :class="`btn ${
              cv_status === 2
                ? 'private-active'
                : `${
                  cv_status === 3 ? 'disable-btn-offer' : 'private-deactive'
                }`
            }`"
          >
            <span>{{ $t('HR_LIST.STATUS_PRIVATE') }}</span>
          </div>

          <div
            :class="`btn ${cv_status === 3 ? 'offer-active' : 'offer-disable'}`"
          >
            <span>{{ $t('HR_LIST.STATUS_OFFICIAL_OFFER_CONFIRM') }}</span>
          </div>
        </div>

        <div class="information-general-select-file">
          <div class="d-flex flex-row align-items-center">
            <span style="line-height: 21px; font-size: 14px" class="mr-2">{{
              $t('RESUME')
            }}</span>
          </div>

          <div class="d-flex flex-row align-items-center">
            <b-link
              :href="getLinkFile(formData.file_c_v?.file_path)"
              target="_blank"
              class="file-content"
            >
              {{ formData.file_c_v?.file_name }}
            </b-link>
          </div>

          <div class="d-flex flex-row align-items-center mt-3">
            <span style="line-height: 21px; font-size: 14px" class="mr-2">{{
              $t('CV')
            }}</span>
          </div>

          <div class="d-flex flex-row align-items-center">
            <b-link
              :href="getLinkFile(formData.file_job?.file_path)"
              target="_blank"
              class="file-content"
            >
              {{ formData.file_job?.file_name }}
            </b-link>
          </div>

          <div
            v-if="file_others.length > 0"
            class="d-flex flex-row align-items-center mt-3"
          >
            <span style="line-height: 21px; font-size: 14px" class="mr-2">{{
              $t('OTHER_DOCUMENT')
            }}</span>
          </div>

          <div
            v-for="(item, index) in file_others"
            :key="`file-other-${index}`"
            class="d-flex flex-row align-items-center"
          >
            <b-link
              :href="getLinkFile(item.file_path)"
              target="_blank"
              class="file-content"
            >
              {{ item.name }}
            </b-link>
          </div>
        </div>
      </div>
    </div>

    <b-modal
      ref="modal-select-job-to-offer"
      v-model="statusModalSelectJobsToOffer"
      title=""
      hide-footer
      dialog-class
      :size="'xl'"
      :no-fade="false"
      no-close-on-backdrop
      aria-describedby="select-jobs-to-offer"
    >
      <SelectJobsToOffer
        :status="true"
        :hr-id="hrIndentify"
        :hr-full-name="formData.full_name"
        :hr-full-name-jp="formData.full_name_ja"
        @clicked-something="handleClickInParent"
        @close-modal="handleCloseModal"
      />
    </b-modal>
  </div>
</template>

<script>
import FavoriteBtn from '@/components/FavoriteBtn/index.vue';
import FavoriteBtnRemoved from '@/components/FavoriteBtnRemoved';
import SelectJobsToOffer from '@/pages/Hr/HrBasicInformation/SelectJobsToOffer.vue';

import { getListCompany } from '@/api/modules/company';
import { userAddFavorite, destroyFavorite } from '@/api/hr.js';
import { MakeToast } from '../../../utils/toastMessage';

const urlAPI = {
  urlGetListCompany: '/company',
};

export default {
  name: 'HrBasicInformation',
  components: {
    FavoriteBtn,
    FavoriteBtnRemoved,
    SelectJobsToOffer,
  },
  props: {
    basicInformation: {
      type: Object,
      require: true,
      default: function() {
        return {};
      },
    },
    type: {
      type: String,
      require: true,
      default: function() {
        return 'detail';
      },
    },
    hrIndentify: {
      type: Number,
      require: true,
      default: function() {
        return 0;
      },
    },

    // setOpen: {
    //   type: Boolean,
    //   require: false,
    //   default: function() {
    //     return false;
    //   },
    // },
  },
  data() {
    return {
      role: {
        hr_green: true,
        hr_pink: true,
      },

      cv_status: 0,

      statusModalSelectJobsToOffer: false,

      favorite_added: true,

      formData: {},

      companyList: [],

      company_name_en: '',

      company_name_jp: '',

      cv_file_name: '選択されていません',

      jd_file_name: '選択されていません',

      file_others: [],

      role_offer: [1, 2, 4],
      role_favourite: [4],
    };
  },
  computed: {
    checkRoleOffer() {
      const profile = this.$store.getters.profile;
      const ROLE = profile.type || 0;
      return this.role_offer.includes(ROLE);
    },
    checkRoleFavourite() {
      const profile = this.$store.getters.profile;
      const ROLE = profile.type || 0;
      return this.role_favourite.includes(ROLE);
    },
    setOpen() {
      return this.$store.getters.setOpen;
    },
  },
  watch: {
    basicInformation: {
      handler: function(props_data) {
        if (props_data) {
          this.formData = props_data;
          this.handleUpdatDataDetail(props_data);
        }
      },
      deep: true,
    },
  },

  created() {
    // this.handleGetListCompany();
    // Lấy set trong store
    if (this.setOpen) {
      this.statusModalSelectJobsToOffer = this.setOpen;
    }
  },
  methods: {
    handleCloseModal() {
      this.statusModalSelectJobsToOffer = false;
    },
    getLinkFile(path) {
      const link = `${process.env.MIX_LARAVEL_TEST_URL}${path}`;
      return link;
    },
    handleUpdatDataDetail(data) {
      this.cv_status = data.status;
      this.favorite_added = data.is_favorite === 1;

      this.company_name_en = data.hr_org.corporate_name_en;
      this.company_name_jp = data.hr_org.corporate_name_ja;

      if (data.file_others) {
        this.file_others = JSON.parse(data.file_others);
      }
    },
    handleClickInParent: function(data) {
      this.statusModalSelectJobsToOffer = data;
    },
    handleAddedToFavorites() {
      if (!this.favorite_added) {
        this.handleAddFavorite();
      }
      if (this.favorite_added) {
        this.handleRemoveFavorite();
      }
      // this.favorite_added = !this.favorite_added;
    },
    async handleAddFavorite() {
      if (!this.formData.id) {
        return;
      }
      try {
        const PARAM = {
          relation_id: this.formData.id,
          type: 1,
        };
        const res = await userAddFavorite(PARAM);
        const { code, message } = res.data;
        if (code === 200) {
          this.favorite_added = true;
        } else {
          MakeToast({
            variant: 'warning',
            title: 'WARNING',
            content: message,
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    async handleRemoveFavorite() {
      if (!this.formData.id) {
        return;
      }
      try {
        const query = `relation_id=${this.formData.id}&type=1`;
        const res = await destroyFavorite(query);
        const { code, message } = res.data;
        if (code === 200) {
          this.favorite_added = false;
        } else {
          MakeToast({
            variant: 'warning',
            title: 'WARNING',
            content: message,
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    handleToggleModalSelectJobsToOffer() {
      if (this.cv_status !== 1) {
        return;
      }
      this.statusModalSelectJobsToOffer = !this.statusModalSelectJobsToOffer;
    },
    async handleGetListCompany() {
      try {
        const URL = `${urlAPI.urlGetListCompany}`;

        const response = await getListCompany(URL);

        if (response['code'] === 200) {
          if (response['data']) {
            response['data']['result'].forEach((element) => {
              this.companyList.push({
                value: element['id'],
                user_id: element['user_id'],
                text_en: element['company_name'],
                text_ja: element['company_name_jp'],
              });
            });
          }

          this.company_name_en = this.handleDisplayCompanyName(
            this.formData['user_id']
          )[0];
          this.company_name_jp = this.handleDisplayCompanyName(
            this.formData['user_id']
          )[1];
        } else {
          console.log('[ERROR]');
        }
      } catch (error) {
        console.log(error);
      }
    },
    handleDisplayCompanyName(user_id) {
      let result = ['', ''];

      this.companyList.find((item) => {
        if (item['user_id'] === parseInt(user_id)) {
          result = [item.text_en, item.text_ja];
        }
      });

      return result;
    },
    openFileSelectCV() {
      this.$refs.fileInputCV.click();
    },
    openFileSelectJD() {
      this.$refs.fileInputJD.click();
    },
    handleFileSelectCV(event) {
      const selectedFile = event.target.files[0];
      this.cv_file_name = `${selectedFile['name']} (${this.bytesToMB(
        selectedFile['size']
      )}MB)`;
    },
    handleFileSelectJD(event) {
      const selectedFile = event.target.files[0];
      this.jd_file_name = `${selectedFile['name']} (${this.bytesToMB(
        selectedFile['size']
      )}MB)`;
    },
    bytesToMB(bytes) {
      const megabytes = bytes / (1024 * 1024);
      return megabytes.toFixed(2);
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';

.file-content {
  max-width: 100%;
  word-break: break-word;
}

.disable-offer {
  background-color: #999 !important;
  border-color: #999 !important;
}

.hr-basic-information {
  width: 25%;
  display: flex;
  min-width: 274px;
  align-items: stretch;
  padding: 0rem 0 1.5rem 0;
  justify-content: flex-start;
}

.hr-basic-information-wrap {
  width: 100%;
  margin-left: 2px;
  display: flex;
  border-radius: 3px;
  background: #ffffff;
  align-items: stretch;
  border: 1px solid red;
  padding: 1.25rem 1rem;
  flex-direction: column;
  border: 1px solid #a0a0a0;
  justify-content: flex-start;
}

.hr-basic-information-avata {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;

  & img {
    width: 100%;
    max-width: 200px;
  }
}

.hr-basic-information-general {
  flex: 1;
  width: 100%;
  display: flex;
  align-items: center;
  flex-direction: column;
  justify-content: flex-start;
}

.infor-first {
  width: 100%;
  display: flex;
  color: #333333;
  font-weight: 400;
  align-items: center;
  flex-direction: column;
  justify-content: center;
  overflow-wrap: anywhere;
  & span:nth-child(1) {
    font-size: 24px;
    line-height: 36px;
  }

  & span:nth-child(2) {
    margin-top: 1px;
    font-size: 20px;
    line-height: 30px;
  }

  & span:nth-child(3) {
    font-size: 16px;
    line-height: 24px;
    margin-top: 0.5rem;
  }
}

.infor-company {
  display: flex;
  color: #333333;
  margin-top: 1rem;
  font-weight: 400;
  align-items: center;
  flex-direction: column;
  justify-content: center;
  overflow-wrap: anywhere;
  & span {
    font-size: 14px;
    line-height: 21px;
  }
}

.information-favorire-btns {
  gap: 1.25rem;
  display: flex;
  border-radius: 3px;
  width: fit-content;
  margin-top: 0.75rem;
  align-items: center;
  flex-direction: column;
  justify-content: center;
}

.information-detail-btns {
  gap: 2px;
  width: 100%;
  height: 38px;
  color: $white;
  display: flex;
  min-width: 152px;
  background: #f9be00;
  align-items: center;
  justify-content: center;
  border: 1px solid #f9be00;
  margin-top: 1rem;
}

.favorite-btn {
  gap: 4px;
  width: 100%;
  height: 38px;
  display: flex;
  min-width: 152px;
  background: #ffffff;
  align-items: center;
  justify-content: center;
  border: 1px solid #cdcdcd;
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);

  & > div {
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
  }
}

.added-to-favorites {
  color: #595959;
  font-size: 12px;
  font-weight: 400;
  line-height: 18px;
}

.removed-to-favorites {
  font-size: 7px;
  color: #cccccc;
  font-weight: 400;
  font-style: normal;
  line-height: 10.5px;
}

.information-general-btns {
  width: 100%;
  gap: 0.5rem;
  display: flex;
  margin-top: 2.37rem;
  align-items: center;
  flex-direction: column;
  justify-content: center;

  & > div {
    height: 38px;
    display: flex;
    color: #ffffff;
    min-width: 100px;
    padding: 0 0.5rem;
    width: fit-content;
    border-radius: 10px;
    background: #ffffff;
    align-items: center;
    justify-content: center;
    border: 1px solid #999999;

    & span {
      font-size: 20px;
      font-weight: 400;
      line-height: 30px;
    }
  }

  .disable-btn-offer {
    border-radius: 10px;
    border: 1px solid #999;
    background: #fff;
    color: #999;
  }

  .offer-active {
    border-radius: 10px;
    border: 1px solid #999;
    background: #666;
    color: #ffffff;
  }

  .offer-disable {
    border-radius: 10px;
    border: 1px solid #d7d7d7;
    background: #fff;
    color: #d7d7d7;
  }
}

.public-active {
  color: #ffffff !important;
  background: #4056fc !important;
  border: 1px solid #4056fc !important;
}

.public-deactive {
  color: #999999 !important;
  background: #ffffff !important;
  border: 1px solid #999999 !important;
}

.private-active {
  color: #ffffff !important;
  background: #999999 !important;
  border: 1px solid #999999 !important;
}

.private-deactive {
  color: #999999 !important;
  background: #ffffff !important;
  border: 1px solid #999999 !important;
}

.information-general-select-file {
  width: 100%;
  margin-top: 2.9rem;
  align-items: center;
  flex-direction: column;
  justify-content: center;
}

::v-deep .modal-header {
  border-bottom: none !important;
}

.file-btn {
  width: 100px;
  display: flex;
  margin-top: 10px;
  color: #333333;
  align-items: center;
  border-radius: 5px !important;
  background-color: #ffffff !important;
  border: 1px solid #868686 !important;

  .file-btn-text {
    font-size: 10px;
  }
}

.file-name {
  display: flex;
  font-size: 10px;
  margin-top: 5px;
}

.fas:hover {
  cursor: pointer;
}
</style>
