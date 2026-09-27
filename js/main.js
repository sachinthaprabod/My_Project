/**
 * Hotel S&B - Main Interactive JavaScript
 * ICT 2209 Web Technologies Mini Project - Rajarata University of Sri Lanka
 * 
 * Features Implemented:
 * 1. Dynamic Content Updates (Filtering menu categories, live search, cart calculations)
 * 2. Interactive Image Slider (Carousel controls & indicators)
 * 3. Form Validation (Client-side real-time validation for Auth, Contact, and Checkout)
 * 4. Smooth Scrolling (Navigation hash links)
 * 5. Event Handling (Tooltips, modal dialogs, toast notifications, quantity buttons)
 * 6. Custom Animations (Card fade-in transitions)
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // =========================================================================
  // 1. Initialize Bootstrap Tooltips & Popovers (Feature 5: Event Handling)
  // =========================================================================
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // =========================================================================
  // 2. Smooth Scrolling for Navigation Links (Feature 4: Smooth Scrolling)
  // =========================================================================
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      if (targetId && targetId !== '#' && targetId.length > 1) {
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          e.preventDefault();
          targetElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      }
    });
  });

  // =========================================================================
  // 3. Dynamic Menu Category Filtering & Live Search (Feature 1: Dynamic Updates)
  // =========================================================================
  const filterButtons = document.querySelectorAll('.filter-btn');
  const foodCards = document.querySelectorAll('.food-item-col');
  const searchInput = document.getElementById('foodSearchInput');
  const noResultsMsg = document.getElementById('noMenuResults');

  function applyFilters() {
    const activeBtn = document.querySelector('.filter-btn.active');
    const selectedCategory = activeBtn ? activeBtn.getAttribute('data-category') : 'All';
    const searchTerm = searchInput ? searchInput.value.trim().toLowerCase() : '';

    let visibleCount = 0;

    foodCards.forEach(card => {
      const category = card.getAttribute('data-category') || '';
      const name = (card.getAttribute('data-name') || '').toLowerCase();
      const desc = (card.getAttribute('data-desc') || '').toLowerCase();

      const matchesCategory = (selectedCategory === 'All' || category.toLowerCase() === selectedCategory.toLowerCase());
      const matchesSearch = (!searchTerm || name.includes(searchTerm) || desc.includes(searchTerm));

      if (matchesCategory && matchesSearch) {
        card.style.display = 'block';
        card.style.opacity = '0';
        setTimeout(() => {
          card.style.transition = 'opacity 0.35s ease';
          card.style.opacity = '1';
        }, 10);
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    if (noResultsMsg) {
      noResultsMsg.style.display = visibleCount === 0 ? 'block' : 'none';
    }
  }

  if (filterButtons.length > 0) {
    filterButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        filterButtons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        applyFilters();
      });
    });
  }

  if (searchInput) {
    searchInput.addEventListener('input', applyFilters);
  }

  // =========================================================================
  // 4. Interactive Shopping Cart System (Feature 1, 5: Dynamic State & Events)
  // =========================================================================
  const cartStorageKey = 'hotel_sb_cart_items';
  let cart = [];

  try {
    const savedCart = localStorage.getItem(cartStorageKey);
    if (savedCart) {
      cart = JSON.parse(savedCart);
    }
  } catch (err) {
    cart = [];
  }

  function saveCart() {
    localStorage.setItem(cartStorageKey, JSON.stringify(cart));
    updateCartUI();
  }

  function updateCartUI() {
    const totalCount = cart.reduce((acc, item) => acc + item.qty, 0);
    const totalPrice = cart.reduce((acc, item) => acc + (item.qty * item.price), 0);

    // Update nav counters
    document.querySelectorAll('.cart-count-badge').forEach(badge => {
      badge.textContent = totalCount;
      badge.style.display = totalCount > 0 ? 'inline-flex' : 'none';
    });

    // Update Drawer / Offcanvas list
    const cartContainer = document.getElementById('cartItemsContainer');
    const cartTotalEl = document.getElementById('cartDrawerTotal');
    const checkoutBtn = document.getElementById('btnCartCheckout');
    const emptyCartMsg = document.getElementById('emptyCartNotice');

    if (cartContainer) {
      if (cart.length === 0) {
        cartContainer.innerHTML = '';
        if (emptyCartMsg) emptyCartMsg.style.display = 'block';
        if (checkoutBtn) checkoutBtn.setAttribute('disabled', 'disabled');
      } else {
        if (emptyCartMsg) emptyCartMsg.style.display = 'none';
        if (checkoutBtn) checkoutBtn.removeAttribute('disabled');

        cartContainer.innerHTML = cart.map(item => `
          <div class="cart-item-row" data-id="${item.id}">
            <div class="cart-item-info">
              <div class="cart-item-name">${item.name}</div>
              <div class="cart-item-price">Rs. ${item.price.toFixed(2)} &times; ${item.qty} = <strong>Rs. ${(item.price * item.qty).toFixed(2)}</strong></div>
            </div>
            <div class="cart-qty-controls">
              <button type="button" class="btn-qty btn-qty-decrease" data-id="${item.id}" title="Decrease quantity">&minus;</button>
              <span class="qty-display">${item.qty}</span>
              <button type="button" class="btn-qty btn-qty-increase" data-id="${item.id}" title="Increase quantity">&plus;</button>
            </div>
            <button type="button" class="btn btn-sm btn-link text-danger ms-2 btn-remove-item" data-id="${item.id}" title="Remove item">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        `).join('');
      }
    }

    if (cartTotalEl) {
      cartTotalEl.textContent = 'Rs. ' + totalPrice.toFixed(2);
    }

    // Update Hidden JSON input on checkout forms if present
    const checkoutCartInput = document.getElementById('checkoutCartData');
    if (checkoutCartInput) {
      checkoutCartInput.value = JSON.stringify(cart);
    }

    const checkoutModalTotal = document.getElementById('checkoutModalGrandTotal');
    if (checkoutModalTotal) {
      checkoutModalTotal.textContent = 'Rs. ' + totalPrice.toFixed(2);
    }
  }

  // Add to cart buttons
  document.querySelectorAll('.btn-add-to-cart').forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      const id = parseInt(this.getAttribute('data-id'), 10);
      const name = this.getAttribute('data-name');
      const price = parseFloat(this.getAttribute('data-price'));

      const existingIndex = cart.findIndex(item => item.id === id);
      if (existingIndex > -1) {
        cart[existingIndex].qty += 1;
      } else {
        cart.push({ id, name, price, qty: 1 });
      }

      saveCart();
      showToast(`${name} added to your order!`, 'success');

      // Button micro-animation
      this.classList.add('btn-success');
      this.innerHTML = '<i class="bi bi-check-lg"></i> Added';
      setTimeout(() => {
        this.classList.remove('btn-success');
        this.innerHTML = '<i class="bi bi-bag-plus"></i> Add to Order';
      }, 1000);
    });
  });

  // Quantity control delegation in Cart Offcanvas
  const cartDrawer = document.getElementById('cartDrawer');
  if (cartDrawer) {
    cartDrawer.addEventListener('click', function (e) {
      const target = e.target.closest('button');
      if (!target) return;

      const id = parseInt(target.getAttribute('data-id'), 10);
      if (target.classList.contains('btn-qty-increase')) {
        const item = cart.find(i => i.id === id);
        if (item) {
          item.qty += 1;
          saveCart();
        }
      } else if (target.classList.contains('btn-qty-decrease')) {
        const itemIndex = cart.findIndex(i => i.id === id);
        if (itemIndex > -1) {
          if (cart[itemIndex].qty > 1) {
            cart[itemIndex].qty -= 1;
          } else {
            cart.splice(itemIndex, 1);
          }
          saveCart();
        }
      } else if (target.classList.contains('btn-remove-item') || target.closest('.btn-remove-item')) {
        cart = cart.filter(i => i.id !== id);
        saveCart();
        showToast('Item removed from cart', 'info');
      }
    });
  }

  // Initial cart UI render
  updateCartUI();

  // If server set order success, clear local cart
  if (window.ORDER_PLACED_SUCCESS === true) {
    cart = [];
    saveCart();
  }

  // =========================================================================
  // 5. Toast Notification System (Feature 5 & 6: Events & Animations)
  // =========================================================================
  function showToast(message, type = 'success') {
    let container = document.querySelector('.sb-toast-container');
    if (!container) {
      container = document.createElement('div');
      container.className = 'sb-toast-container';
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `sb-toast border-${type}`;
    const icon = type === 'success' ? 'bi-check-circle-fill text-success' : (type === 'danger' ? 'bi-exclamation-triangle-fill text-danger' : 'bi-info-circle-fill text-primary');

    toast.innerHTML = `
      <i class="bi ${icon} fs-5"></i>
      <div class="flex-grow-1 font-weight-bold" style="font-size: 0.95rem;">${message}</div>
      <button type="button" class="btn-close ms-2" aria-label="Close"></button>
    `;

    container.appendChild(toast);

    toast.querySelector('.btn-close').addEventListener('click', () => {
      toast.remove();
    });

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transition = 'opacity 0.4s ease';
      setTimeout(() => toast.remove(), 400);
    }, 3500);
  }

  // Expose showToast globally
  window.showToast = showToast;

  // =========================================================================
  // 6. Comprehensive Client-Side Form Validation (Feature 3: Form Validation)
  // =========================================================================

  // Helper validation functions
  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function isValidSriLankanPhone(phone) {
    // Matches 10 digits starting with 07, 011, 025, etc., or +94 format
    const cleanPhone = phone.replace(/[\s\-]/g, '');
    return /^(0|(\+?94))?[0-9]{9,10}$/.test(cleanPhone);
  }

  // 6.1 Registration Form Validation (auth/register.php)
  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', function (e) {
      let isValid = true;

      const username = document.getElementById('reg_username');
      const email = document.getElementById('reg_email');
      const password = document.getElementById('reg_password');
      const confirmPassword = document.getElementById('reg_confirm_password');
      const phone = document.getElementById('reg_phone');

      // Validate Username
      if (!username.value.trim() || username.value.trim().length < 3) {
        setInvalid(username, 'Username must be at least 3 characters.');
        isValid = false;
      } else {
        setValid(username);
      }

      // Validate Email
      if (!isValidEmail(email.value.trim())) {
        setInvalid(email, 'Please enter a valid email address.');
        isValid = false;
      } else {
        setValid(email);
      }

      // Validate Password
      if (!password.value || password.value.length < 6) {
        setInvalid(password, 'Password must be at least 6 characters long.');
        isValid = false;
      } else {
        setValid(password);
      }

      // Validate Confirm Password
      if (confirmPassword.value !== password.value || !confirmPassword.value) {
        setInvalid(confirmPassword, 'Passwords do not match.');
        isValid = false;
      } else {
        setValid(confirmPassword);
      }

      // Validate Phone (optional but if entered must match format)
      if (phone && phone.value.trim() && !isValidSriLankanPhone(phone.value.trim())) {
        setInvalid(phone, 'Please enter a valid Sri Lankan phone number (e.g. 0771234567).');
        isValid = false;
      } else if (phone && phone.value.trim()) {
        setValid(phone);
      }

      if (!isValid) {
        e.preventDefault();
      }
    });
  }

  // 6.2 Login Form Validation (auth/login.php)
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      let isValid = true;

      const identifier = document.getElementById('login_identifier');
      const password = document.getElementById('login_password');

      if (!identifier.value.trim()) {
        setInvalid(identifier, 'Please enter your username or email.');
        isValid = false;
      } else {
        setValid(identifier);
      }

      if (!password.value) {
        setInvalid(password, 'Please enter your password.');
        isValid = false;
      } else {
        setValid(password);
      }

      if (!isValid) {
        e.preventDefault();
      }
    });
  }

  // 6.3 Contact Form Validation (contact.php)
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      let isValid = true;

      const name = document.getElementById('contact_name');
      const email = document.getElementById('contact_email');
      const subject = document.getElementById('contact_subject');
      const message = document.getElementById('contact_message');

      if (!name.value.trim() || name.value.trim().length < 2) {
        setInvalid(name, 'Please enter your name.');
        isValid = false;
      } else {
        setValid(name);
      }

      if (!isValidEmail(email.value.trim())) {
        setInvalid(email, 'Please enter a valid email address.');
        isValid = false;
      } else {
        setValid(email);
      }

      if (subject && !subject.value.trim()) {
        setInvalid(subject, 'Please enter a message subject.');
        isValid = false;
      } else if (subject) {
        setValid(subject);
      }

      if (!message.value.trim() || message.value.trim().length < 10) {
        setInvalid(message, 'Message must be at least 10 characters long.');
        isValid = false;
      } else {
        setValid(message);
      }

      if (!isValid) {
        e.preventDefault();
      }
    });
  }

  // 6.4 Checkout Modal Form Validation
  const checkoutForm = document.getElementById('checkoutOrderForm');
  if (checkoutForm) {
    checkoutForm.addEventListener('submit', function (e) {
      if (cart.length === 0) {
        e.preventDefault();
        alert('Your shopping cart is empty! Please add items before checking out.');
        return;
      }

      let isValid = true;
      const name = document.getElementById('order_customer_name');
      const phone = document.getElementById('order_customer_phone');
      const address = document.getElementById('order_delivery_address');

      if (name && (!name.value.trim() || name.value.trim().length < 2)) {
        setInvalid(name, 'Please enter your delivery contact name.');
        isValid = false;
      } else if (name) {
        setValid(name);
      }

      if (phone && !isValidSriLankanPhone(phone.value.trim())) {
        setInvalid(phone, 'Please enter a valid Sri Lankan phone number (e.g. 0771234567).');
        isValid = false;
      } else if (phone) {
        setValid(phone);
      }

      if (address && (!address.value.trim() || address.value.trim().length < 8)) {
        setInvalid(address, 'Please provide your full delivery address.');
        isValid = false;
      } else if (address) {
        setValid(address);
      }

      if (!isValid) {
        e.preventDefault();
      } else {
        // Sync cart data into hidden input
        const cartInput = document.getElementById('checkoutCartData');
        if (cartInput) {
          cartInput.value = JSON.stringify(cart);
        }
      }
    });
  }

  // Validation styling helpers
  function setInvalid(element, message) {
    element.classList.add('is-invalid');
    element.classList.remove('is-valid');
    let feedback = element.parentElement.querySelector('.invalid-feedback');
    if (!feedback) {
      feedback = document.createElement('div');
      feedback.className = 'invalid-feedback';
      element.parentElement.appendChild(feedback);
    }
    feedback.textContent = message;
    feedback.style.display = 'block';
  }

  function setValid(element) {
    element.classList.remove('is-invalid');
    element.classList.add('is-valid');
    const feedback = element.parentElement.querySelector('.invalid-feedback');
    if (feedback) {
      feedback.style.display = 'none';
    }
  }

  // Real-time input clearing of errors on keyup
  document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('input', function () {
      if (this.classList.contains('is-invalid')) {
        this.classList.remove('is-invalid');
      }
    });
  });

});
