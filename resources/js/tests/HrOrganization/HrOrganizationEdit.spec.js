import { shallowMount, mount } from '@vue/test-utils';
import router from '@/router';
// import {
//   validEmptyOrWhiteSpace,
//   validEmail,
//   validPassword,
//   validateNumberMoreThanZero,
// } from '@/utils/validate';
import HROganizationEdit from '@/pages/HrOrganization/edit.vue';

const CONPONENT_NAME = 'HR Organization Edit';

describe(`COMPONENT: ${CONPONENT_NAME}`, () => {
  test('Case 1: Test component render screen, title', () => {
    const wrapper = shallowMount(HROganizationEdit, {
      router,
    });

    const title = wrapper.find('.hr-org-edit-title');
    expect(title.exists()).toBe(true);

    expect(title.text()).toEqual('TITLE.ORGANIZATION_DETAIL');

    // expect(wrapper.vm.User).toStrictEqual(USER);

    wrapper.destroy();
  });
  test('Case 2: Test component render DATA', () => {
    const wrapper = shallowMount(HROganizationEdit, {
      router,
    });

    const OVERLAY = {
      opacity: 0,
      show: true,
      blur: '1rem',
      rounded: 'sm',
      variant: 'light',
      fixed: true,
    };

    const FORM_DATA = {
      corporate_name_en: '',
      corporate_name_ja: '',
      license_no: '',
      account_classification: 0,
      country: 0,
      representative_full_name: '',
      representative_full_name_furigana: '',
      representative_contact: '',
      representative_contact_code: '',
      assignee_contact: '',
      assignee_contact_code: '',
      post_code: '',
      prefectures: '',
      municipality: '',
      number: '',
      other: '',
      mail_address: '',
      url: '',
      certificate_file_id: '',
    };

    expect(wrapper.vm.overlay).toStrictEqual(OVERLAY);
    expect(wrapper.vm.formData).toStrictEqual(FORM_DATA);

    wrapper.destroy();
  });

  test('Case 3: Test all field', () => {
    const wrapper = mount(HROganizationEdit, {
      router,
    });

    const label_input_file = wrapper.find('.label-input-file');
    expect(label_input_file.exists()).toBe(true);
    expect(label_input_file.text()).toEqual('許可証');
    // expect(label_input_file).toMatchSnapshot();
    const input_file = wrapper.find('.input-file');
    expect(input_file.exists()).toBe(true);

    const change_status_hr_org = wrapper.find('.change-status_hr-org');
    expect(change_status_hr_org.exists()).toBe(true);
    // const input_file = wrapper.find('.input-file');
    // expect(input_file.exists()).toBe(true);

    const label_input_hrorg_name = wrapper.find('.label-name-hrorg-en');
    expect(label_input_hrorg_name.exists()).toBe(true);
    expect(label_input_hrorg_name.text()).toEqual('HR_REGISTER.LABEL.CORPORATE_NAME');
    const input_hrorg_name = wrapper.find('.input-hrorg-name-en');
    expect(input_hrorg_name.exists()).toBe(true);

    const label_input_hrorg_name_ja = wrapper.find('.label-name-hrorg-ja');
    expect(label_input_hrorg_name_ja.exists()).toBe(true);
    expect(label_input_hrorg_name_ja.text()).toEqual('HR_REGISTER.LABEL.CORPORATE_NAME_JAPAN');
    const input_hrorg_name_ja = wrapper.find('.input-name-hrorg-ja');
    expect(input_hrorg_name_ja.exists()).toBe(true);

    const label_license_no = wrapper.find('.label-license-no');
    expect(label_license_no.exists()).toBe(true);
    expect(label_license_no.text()).toEqual('HR_REGISTER.LABEL.LICENSE_NO');
    const license_no = wrapper.find('.input-license-no');
    expect(license_no.exists()).toBe(true);

    const label_account_classification = wrapper.find('.label-account-classification');
    expect(label_account_classification.exists()).toBe(true);
    expect(label_account_classification.text()).toEqual('HR_REGISTER.LABEL.ACCOUNT_CLASSIFICATION');
    const account_classification = wrapper.find('.input-account-classification');
    expect(account_classification.exists()).toBe(true);

    const label_country = wrapper.find('.label-county');
    expect(label_country.exists()).toBe(true);
    expect(label_country.text()).toEqual('HR_REGISTER.LABEL.CONTRY');
    const select_country = wrapper.find('.select-country');
    expect(select_country.exists()).toBe(true);

    const label_representative_full_name = wrapper.find('.label-representative-full-name');
    expect(label_representative_full_name.exists()).toBe(true);
    expect(label_representative_full_name.text()).toEqual('HR_REGISTER.LABEL.REPRESENTATIVE_FULL_NAME');
    const input_representative_full_name = wrapper.find('.input-representative-full-name');
    expect(input_representative_full_name.exists()).toBe(true);

    const label_representative_full_name_firigama = wrapper.find('.label-representative-full-name-furigama');
    expect(label_representative_full_name_firigama.exists()).toBe(true);
    expect(label_representative_full_name_firigama.text()).toEqual('HR_REGISTER.LABEL.REPRESENTATIVE_FULL_NAME_FURIGANA');
    const input_representative_full_name_firigama = wrapper.find('.input-representative-full-name-furigama');
    expect(input_representative_full_name_firigama.exists()).toBe(true);

    const label_representative_contact = wrapper.find('.label-representative-contact');
    expect(label_representative_contact.exists()).toBe(true);
    expect(label_representative_contact.text()).toEqual('COMPANY.REPRESENTATIVE_CONTACT');
    const input_representative_contact = wrapper.find('.input-representative-contact');
    expect(input_representative_contact.exists()).toBe(true);

    const label_assignee_contact = wrapper.find('.label-assignee-contact');
    expect(label_assignee_contact.exists()).toBe(true);
    expect(label_assignee_contact.text()).toEqual('COMPANY.ASSIGNEE_CONTACT');
    const input_assignee_contact = wrapper.find('.input-assignee-contact');
    expect(input_assignee_contact.exists()).toBe(true);

    const label_address = wrapper.find('.label-address');
    expect(label_address.exists()).toBe(true);
    expect(label_address.text()).toEqual('COMPANY.ADDRESS');
    // //
    const label_post_code = wrapper.find('.label-post-code');
    expect(label_post_code.exists()).toBe(true);
    expect(label_post_code.text()).toEqual('COMPANY.POST_CODE');
    const input_post_code = wrapper.find('.input-post-code');
    expect(input_post_code.exists()).toBe(true);
    // //
    const label_distinct = wrapper.find('.label-distinct');
    expect(label_distinct.exists()).toBe(true);
    expect(label_distinct.text()).toEqual('COMPANY.DISTINCT');
    const input_distinct = wrapper.find('.input-distinct');
    expect(input_distinct.exists()).toBe(true);

    const label_mail_address = wrapper.find('.label-mail-address');
    expect(label_mail_address.exists()).toBe(true);
    expect(label_mail_address.text()).toEqual('COMPANY.MAIL_ADDRESS');
    const input_mail_address = wrapper.find('.input-mail-address');
    expect(input_mail_address.exists()).toBe(true);

    const label_url = wrapper.find('.label-url');
    expect(label_url.exists()).toBe(true);
    expect(label_url.text()).toEqual('URL');
    const input_url = wrapper.find('.input-url');
    expect(input_url.exists()).toBe(true);

    const label_certificate = wrapper.find('.label-certificate');
    expect(label_certificate.exists()).toBe(true);
    expect(label_certificate.text()).toEqual('HR_REGISTER.LABEL.CERTIFICATE');
    const input_certificate = wrapper.find('.input-certificate');
    expect(input_certificate.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 4: Test all field requied', async() => {
    const wrapper = mount(HROganizationEdit, {
      router,
    });

    const requied = wrapper.findAll('.badge-required');
    expect(requied.exists()).toBe(true);

    expect(requied.length).toEqual(13);

    wrapper.destroy();
  });

  test('Case 5: Test change status', async() => {
    const wrapper = mount(HROganizationEdit, {
      router,
    });

    const change_status_hr_org = wrapper.find('.change-status_hr-org');
    expect(change_status_hr_org.exists()).toBe(true);
    const count_option_befor = change_status_hr_org.findAll('option');
    expect(count_option_befor.length).toEqual(3);

    await change_status_hr_org.setValue(2);

    const count_option_after = change_status_hr_org.findAll('option');
    expect(count_option_after.length).toEqual(3);

    wrapper.destroy();
  });

  test('Case 6: Test render button cancel edit (back to detail) and click', async() => {
    const wrapper = mount(HROganizationEdit, {
      router,
    });

    const to_detail = wrapper.find('.to-detail-hrorg');
    expect(to_detail.exists()).toBe(true);

    const handleBackToDetail = jest.spyOn(wrapper.vm, 'handleBackToDetail');

    if (expect(to_detail.exists()).toBe(true)) {
      await to_detail.trigger('click');
      expect(handleBackToDetail).toHaveBeenCalled();
    }
    wrapper.destroy();
  });

  test('Case 7: Test render button save and click', async() => {
    const wrapper = mount(HROganizationEdit, {
      router,
    });

    const save_hr_org = wrapper.find('.save-hrorg');
    expect(save_hr_org.exists()).toBe(true);

    const handleConfirmUpdate = jest.spyOn(wrapper.vm, 'handleConfirmUpdate');

    if (expect(save_hr_org.exists()).toBe(true)) {
      await save_hr_org.trigger('click');
      expect(handleConfirmUpdate).toHaveBeenCalled();
    }
    wrapper.destroy();
  });
});
