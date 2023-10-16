import { shallowMount, createLocalVue } from '@vue/test-utils';

import store from '@/store';
import router from '@/router';
import SelectEntryHR from '@/pages/JobSearch/JobEntry/index';

const localVue = createLocalVue();

describe('TEST SCREEN SELECT ENTRY HR', () => {
  let wrapper;

  beforeEach(() => {
    wrapper = shallowMount(SelectEntryHR, {
      mocks: {
        store,
        router,
      },
    });
  });

  test('TEST RENDER COMPONENT SELECT ENTRY HR', () => {
    const wrapper = shallowMount(SelectEntryHR, {
      store,
      router,
      localVue,
    });

    const OVERLAY = {
      opacity: 1,
      show: false,
      blur: '1rem',
      rounded: 'sm',
      variant: 'light',
    };

    expect(wrapper.vm.overlay).toStrictEqual(OVERLAY);

    wrapper.destroy();
  });

  it('CORRECTLY NAVIGATES TO JOB DETAIL WITH WARNING MODAL WHEN LIST_HR_SELECTED IS NOT EMPTY', () => {
    wrapper.vm.list_hr_selected = [
      // mock data
    ];

    wrapper.vm.navigateToJobDetail();

    expect(wrapper.vm.isShowModalWarning).toBeTruthy();
    expect(wrapper.vm.$router.push).not.toHaveBeenCalled();
  });

  it('CORRECTLY NAVIGATES TO JOB DETAIL WITHOUT WARNING MODAL WHEN LIST_HR_SELECTED IS EMPTY', () => {
    wrapper.vm.list_hr_selected = [];

    wrapper.vm.navigateToJobDetail();

    expect(wrapper.vm.isShowModalWarning).toBeFalsy();
    expect(wrapper.vm.$router.push).toHaveBeenCalledWith({ path: '/job-search/detail/your-work-id' });
  });

  it('CORRECTLY NAVIGATES TO JOB INFORMATION ENTRY WHEN LIST_HR_SELECTED IS NOT EMPTY', () => {
    wrapper.vm.list_hr_selected = [
      // mock data
    ];

    wrapper.vm.navigateToInformation();

    expect(wrapper.vm.$router.push).toHaveBeenCalledWith({ name: 'JobInformationEntry' });
  });

  it('DISPLAYS A WARNING TOAST WHEN NAVIGATING TO JOB INFORMATION ENTRY WITH EMPTY LIST_HR_SELECTED', () => {
    const makeToastMock = jest.spyOn(wrapper.vm, 'MakeToast');
    wrapper.vm.navigateToInformation();

    expect(makeToastMock).toHaveBeenCalledWith({
      variant: 'warning',
      title: wrapper.vm.$t('WARNING'),
      content: 'エントリーする人材を選択してください (エントリーに進む ボタン下部)',
    });
  });

  it('CORRECTLY REMOVES HR FROM LIST_HR_SELECTED', () => {
    const hrIdToRemove = 'hr-id-to-remove';
    wrapper.vm.list_hr_selected = [
      // mock data including hrIdToRemove
    ];

    wrapper.vm.removeThisHr(hrIdToRemove);

    expect(wrapper.vm.list_hr_selected).toEqual([
      // expected list after removing hrIdToRemove
    ]);
    expect(wrapper.vm.store.dispatch).toHaveBeenCalledWith('job/setListSelectedHr', [
      // expected list after removing hrIdToRemove
    ]);
  });

  it('CORRECTLY TOGGLES THE STATUS OF THE MODAL FOR SELECTING HR', () => {
    const initialStatus = wrapper.vm.statusModalSelectHR;
    wrapper.vm.handleToggleModalSelectHR();
    expect(wrapper.vm.statusModalSelectHR).toBe(!initialStatus);
  });

  it('CORRECTLY HANDLES CHECKBOX VALUE CHANGE FOR HR SELECTION', () => {
    const mockItem = {
      id: 'mock-id',
      is_check: false,
      is_disabled: false,
    };

    wrapper.vm.handleChangeCheckboxValue(mockItem, true);
    expect(mockItem.is_check).toBeTruthy();

    wrapper.vm.handleChangeCheckboxValue(mockItem, false);
    expect(mockItem.is_check).toBeFalsy();
  });

  it('CORRECTLY DISABLES CHECKBOXES WHEN THE MAXIMUM HR SELECTION LIMIT IS REACHED', () => {
    wrapper.vm.list_temp_hr_selected = [
      // mock data exceeding the limit
    ];
    wrapper.vm.vItems = [
      // mock data for checkboxes
    ];

    wrapper.vm.handleChangeCheckboxValue(wrapper.vm.vItems[0], true);

    wrapper.vm.vItems.forEach(item => {
      if (wrapper.vm.list_temp_hr_selected.length === 10) {
        expect(item.is_disabled).toBeTruthy();
      } else {
        expect(item.is_disabled).toBeFalsy();
      }
    });
  });

  it('CORRECTLY RESETS DISABLED STATUS OF CHECKBOXES WHEN HR SELECTION LIMIT IS NOT REACHED', () => {
    wrapper.vm.vItems = [
      // mock data for checkboxes
    ];

    wrapper.vm.handleResetDisabledStatus();

    wrapper.vm.vItems.forEach(item => {
      expect(item.is_disabled).toBeFalsy();
    });
  });

  it('CORRECTLY HANDLES TURNING OFF THE HR SELECTION MODAL AND UPDATES SELECTED HR LIST', async() => {
    const mockTempSelectedHR = [
      // mock data for temporary selected HR
    ];
    wrapper.vm.list_temp_hr_selected = mockTempSelectedHR;

    await wrapper.vm.handleTurnOffModal();

    expect(wrapper.vm.statusModalSelectHR).toBeFalsy();
    expect(wrapper.vm.list_hr_selected).toEqual(mockTempSelectedHR);
    expect(wrapper.vm.store.dispatch).toHaveBeenCalledWith('job/setListSelectedHr', mockTempSelectedHR);
    expect(wrapper.vm.list_temp_hr_selected).toEqual([]);
  });
});
