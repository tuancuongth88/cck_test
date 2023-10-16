import store from '@/store';
import InterviewCancel from '../../../pages/MatchingManagement/InterviewControl/adjusted.vue';

import { shallowMount, createLocalVue } from '@vue/test-utils';

const localVue = createLocalVue(store);

describe('TEST SCREEN INTERVIEW CANCEL', () => {
  let wrapper;

  beforeEach(() => {
    wrapper = shallowMount(InterviewCancel, {
      localVue,
      propsData: {
        detailData: {
          id: 8,
          entry_code: '1691053958',
          interview_date: '20230806',
          full_name: 'Dinh',
          job_name: 'ディン',
          status_selection: 8,
          status_interview_adjustment: 1,
        },
      },
    });
  });

  afterEach(() => {
    wrapper.destroy();
  });

  it('Test 1: Renders the InterviewCancel component', () => {
    expect(wrapper.exists()).toBe(true);
  });

  it('Test 2: Initializes with step 1', () => {
    expect(wrapper.vm.step).toBe(1);
  });

  it('Test 3: Handles changing select_status', async() => {
    wrapper.vm.select_status = 1;
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.error_select_status).toBe(false);
  });

  it('Test 4: Handles clicking "Next Step" button', async() => {
    wrapper.vm.handleNextStep({ preventDefault: jest.fn() });
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.step).toBe(2);
  });

  it('Test 5: Handles clicking "Back" button', async() => {
    wrapper.vm.step = 2;
    wrapper.vm.handleBack();
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.step).toBe(1);
  });

  it('Test 6: Handles changing action', async() => {
    wrapper.vm.action = 1;
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.error_action).toBe(false);
  });

  it('Test 7: Handles changing date_offer', async() => {
    wrapper.vm.date_offer = '2023-09-10';
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.error_date_offer).toBe(false);
  });

  it('Test 8: Validates form when clicking "Next Step" with missing select_status', async() => {
    wrapper.vm.handleNextStep({ preventDefault: jest.fn() });
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.step).toBe(1); // Should stay on step 1 due to validation error
    expect(wrapper.vm.error_select_status).toBe(true);
  });

  it('Test 9: Validates form when clicking "Next Step" with missing action', async() => {
    wrapper.vm.select_status = 1; // Set select_status
    wrapper.vm.handleNextStep({ preventDefault: jest.fn() });
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.step).toBe(1); // Should stay on step 1 due to validation error
    expect(wrapper.vm.error_action).toBe(true);
  });

  it('Test 10: Validates form when clicking "Next Step" with missing date_offer', async() => {
    wrapper.vm.select_status = 3; // Set select_status to 3
    wrapper.vm.handleNextStep({ preventDefault: jest.fn() });
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.step).toBe(1); // Should stay on step 1 due to validation error
    expect(wrapper.vm.error_date_offer).toBe(true);
  });

  it('Test 11: Handles submitting interview cancellation with note', async() => {
    wrapper.vm.note = 'Cancellation note';
    const putCancelInterviewSpy = jest.spyOn(wrapper.vm, 'putCancelInterview');
    await wrapper.vm.handleStopInterview();
    expect(putCancelInterviewSpy).toHaveBeenCalled();
    putCancelInterviewSpy.mockRestore();
  });

  it('Test 12: Handles submitting interview cancellation without note', async() => {
    const putCancelInterviewSpy = jest.spyOn(wrapper.vm, 'putCancelInterview');
    await wrapper.vm.handleStopInterview();
    expect(putCancelInterviewSpy).toHaveBeenCalled();
    putCancelInterviewSpy.mockRestore();
  });

  it('Test 13: Handles closing the cancel modal', async() => {
    const closeModalSpy = jest.spyOn(wrapper.vm.$eventBus, '$emit');
    wrapper.vm.handleCloseCancel();
    expect(closeModalSpy).toHaveBeenCalledWith('close-modal', 'cancel-modal');
    closeModalSpy.mockRestore();
  });

  it('Test 14: Handles confirming interview company review', async() => {
    const putConfirmReviewSpy = jest.spyOn(wrapper.vm, 'putConfirmReview');
    await wrapper.vm.handleConfirmInterviewCompanyReview();
    expect(putConfirmReviewSpy).toHaveBeenCalled();
    putConfirmReviewSpy.mockRestore();
  });

  it('Test 15: Handles clicking "Back" button on step 0', async() => {
    wrapper.vm.step = 0;
    wrapper.vm.handleBack();
    expect(wrapper.vm.step).toBe(1); // Should stay on step 1 if already on step 0
  });

  it('Test 16: Checks if roleCheck returns true for allowed roles', () => {
    // Simulate having a role that is allowed
    wrapper.vm.$store.getters.profile = { type: 1 }; // Replace with the allowed role
    expect(wrapper.vm.roleCheck).toBe(true);
  });

  it('Test 17: Checks if roleCheck returns false for disallowed roles', () => {
    // Simulate having a role that is not allowed
    wrapper.vm.$store.getters.profile = { type: 3 }; // Replace with a disallowed role
    expect(wrapper.vm.roleCheck).toBe(false);
  });

  it('Test 18: Handles clicking "Close" button on step 0', async() => {
    wrapper.vm.step = 0;
    wrapper.vm.handleBack();
    expect(wrapper.vm.step).toBe(1); // Should stay on step 1 if already on step 0
  });

  it('Test 19: Handles clicking "Next Step" button on step 2 with valid inputs', async() => {
    wrapper.vm.step = 2;
    wrapper.vm.select_status = 1; // Valid select_status
    wrapper.vm.action = 1; // Valid action
    wrapper.vm.handleNextStep({ preventDefault: jest.fn() });
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.step).toBe(3); // Should proceed to step 3 with valid inputs
  });

  it('Test 20: Handles clicking "Next Step" button on step 2 with missing action', async() => {
    wrapper.vm.step = 2;
    wrapper.vm.select_status = 1; // Valid select_status
    wrapper.vm.action = null; // Missing action
    wrapper.vm.handleNextStep({ preventDefault: jest.fn() });
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.step).toBe(2); // Should stay on step 2 due to missing action
  });

  it('Test 21: Handles clicking "Next Step" button on step 2 with missing date_offer', async() => {
    wrapper.vm.step = 2;
    wrapper.vm.select_status = 3; // Valid select_status
    wrapper.vm.date_offer = ''; // Missing date_offer
    wrapper.vm.handleNextStep({ preventDefault: jest.fn() });
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.step).toBe(2); // Should stay on step 2 due to missing date_offer
  });

  it('Test 22: Handles submitting interview cancellation with a note', async() => {
    wrapper.vm.note = 'Cancellation note';
    const putCancelInterviewSpy = jest.spyOn(wrapper.vm, 'putCancelInterview');
    await wrapper.vm.handleStopInterview();
    expect(putCancelInterviewSpy).toHaveBeenCalled();
    putCancelInterviewSpy.mockRestore();
  });

  it('Test 23: Handles submitting interview cancellation without a note', async() => {
    const putCancelInterviewSpy = jest.spyOn(wrapper.vm, 'putCancelInterview');
    await wrapper.vm.handleStopInterview();
    expect(putCancelInterviewSpy).toHaveBeenCalled();
    putCancelInterviewSpy.mockRestore();
  });

  it('Test 24: Handles closing the cancel modal', async() => {
    const closeModalSpy = jest.spyOn(wrapper.vm.$eventBus, '$emit');
    wrapper.vm.handleCloseCancel();
    expect(closeModalSpy).toHaveBeenCalledWith('close-modal', 'cancel-modal');
    closeModalSpy.mockRestore();
  });

  it('Test 25: Handles confirming interview company review', async() => {
    const putConfirmReviewSpy = jest.spyOn(wrapper.vm, 'putConfirmReview');
    await wrapper.vm.handleConfirmInterviewCompanyReview();
    expect(putConfirmReviewSpy).toHaveBeenCalled();
    putConfirmReviewSpy.mockRestore();
  });
});
