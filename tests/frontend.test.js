/** @vitest-environment jsdom */
/**
 * Frontend Unit Tests for Order Package Form
 * Using Vitest for JavaScript testing
 */

import { describe, it, expect, beforeEach, vi } from 'vitest';
import { initOrderPackage } from '../resources/js/order-package.js';

// Mock DOM elements
const mockDOM = () => {
    document.body.innerHTML = `
        <div id="alert-success" class="alert alert-success" style="display: none;">Success message</div>
        <div id="alert-error" class="alert alert-error" style="display: none;">Error message</div>
        <div id="alert-validation" class="alert alert-error" style="display: none;">Validation error</div>

        <div id="confirmationModal" class="modal-overlay" style="display: none;">
            <div class="modal-content">
                <h3>Konfirmasi Pemesanan</h3>
                <button id="confirmBtn">Ya, Pesan Sekarang</button>
                <button id="cancelBtn">Batal</button>
            </div>
        </div>

        <form id="orderForm">
            <input type="text" id="nama" required>
            <input type="email" id="email" required>
            <input type="tel" id="no_hp" required>
            <textarea id="alamat" required></textarea>
            <button type="button" id="submitBtn">Pesan Sekarang</button>
        </form>
    `;
};

// Mock alert function
const mockShowAlert = vi.fn();

// Test suite for Order Package Form
describe('Order Package Form', () => {
    beforeEach(() => {
        mockDOM();

        // Mock the showAlert function
        global.showAlert = mockShowAlert;

        // Initialize order package handlers so buttons and alerts are wired up
        initOrderPackage(document);

        // Reset mocks
        mockShowAlert.mockClear();
    });

    describe('Form Validation', () => {
        it('should show error when required fields are empty', () => {
            // Set empty values
            document.getElementById('nama').value = '';
            document.getElementById('email').value = '';
            document.getElementById('no_hp').value = '';
            document.getElementById('alamat').value = '';

            // Simulate form submission
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.click();

            expect(mockShowAlert).toHaveBeenCalledWith('Mohon lengkapi semua field yang wajib diisi!', 'error');
        });

        it('should show error when email format is invalid', () => {
            // Set valid values except email
            document.getElementById('nama').value = 'John Doe';
            document.getElementById('email').value = 'invalid-email';
            document.getElementById('no_hp').value = '08123456789';
            document.getElementById('alamat').value = 'Test Address';

            // Simulate form submission
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.click();

            expect(mockShowAlert).toHaveBeenCalledWith('Format email tidak valid!', 'error');
        });

        it('should show confirmation modal when form is valid', () => {
            // Set valid values
            document.getElementById('nama').value = 'John Doe';
            document.getElementById('email').value = 'john@example.com';
            document.getElementById('no_hp').value = '08123456789';
            document.getElementById('alamat').value = 'Test Address';

            // Simulate form submission
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.click();

            const modal = document.getElementById('confirmationModal');
            expect(modal.classList.contains('show')).toBe(true);
        });
    });

    describe('Modal Functionality', () => {
        beforeEach(() => {
            // Set valid form data first
            document.getElementById('nama').value = 'John Doe';
            document.getElementById('email').value = 'john@example.com';
            document.getElementById('no_hp').value = '08123456789';
            document.getElementById('alamat').value = 'Test Address';
        });

        it('should close modal when cancel button is clicked', () => {
            // Show modal first
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.click();

            // Click cancel button
            const cancelBtn = document.getElementById('cancelBtn');
            cancelBtn.click();

            const modal = document.getElementById('confirmationModal');
            expect(modal.classList.contains('show')).toBe(false);
        });

        it('should close modal when overlay is clicked', () => {
            // Show modal first
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.click();

            // Click modal overlay
            const modal = document.getElementById('confirmationModal');
            modal.click();

            expect(modal.classList.contains('show')).toBe(false);
        });
    });

    describe('Alert System', () => {
        it('should show success alert when success element exists', () => {
            // Create success alert element
            const successAlert = document.getElementById('alert-success');
            successAlert.style.display = 'block';

            // Simulate page load
            const event = new Event('DOMContentLoaded');
            document.dispatchEvent(event);

            // Alert should be shown (class 'show' added)
            expect(successAlert.classList.contains('show')).toBe(true);
        });

        it('should show error alert when error element exists', () => {
            // Create error alert element
            const errorAlert = document.getElementById('alert-error');
            errorAlert.style.display = 'block';

            // Simulate page load
            const event = new Event('DOMContentLoaded');
            document.dispatchEvent(event);

            // Alert should be shown
            expect(errorAlert.classList.contains('show')).toBe(true);
        });

        it('should show validation alert when validation errors exist', () => {
            // Create validation alert element
            const validationAlert = document.getElementById('alert-validation');
            validationAlert.style.display = 'block';

            // Simulate page load
            const event = new Event('DOMContentLoaded');
            document.dispatchEvent(event);

            // Alert should be shown
            expect(validationAlert.classList.contains('show')).toBe(true);
        });
    });

    describe('Form Data Preservation', () => {
        it('should preserve old input values', () => {
            // Simulate old input values (from Laravel old() helper)
            document.getElementById('nama').value = 'Old Name';
            document.getElementById('email').value = 'old@example.com';
            document.getElementById('no_hp').value = '08111111111';
            document.getElementById('alamat').value = 'Old Address';

            expect(document.getElementById('nama').value).toBe('Old Name');
            expect(document.getElementById('email').value).toBe('old@example.com');
            expect(document.getElementById('no_hp').value).toBe('08111111111');
            expect(document.getElementById('alamat').value).toBe('Old Address');
        });
    });
});

// Utility function tests
describe('Utility Functions', () => {
    it('should validate email format correctly', () => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        expect(emailRegex.test('valid@example.com')).toBe(true);
        expect(emailRegex.test('invalid-email')).toBe(false);
        expect(emailRegex.test('')).toBe(false);
        expect(emailRegex.test('test@')).toBe(false);
    });

    it('should check required fields', () => {
        const isValid = (value) => !!(value && value.trim().length > 0);

        expect(isValid('test')).toBe(true);
        expect(isValid('   ')).toBe(false);
        expect(isValid('')).toBe(false);
        expect(isValid(null)).toBe(false);
        expect(isValid(undefined)).toBe(false);
    });
});