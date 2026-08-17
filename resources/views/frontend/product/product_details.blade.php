@extends('frontend.layout.arthubly')

@section('title', $product->name . ' — Arthubly')

@section('style')

@endsection
@section('content')
    <style>
        /* Hide bottom mobile nav on product details page */
        .pdp-page .mobile-nav {
            display: none !important;
        }
    </style>
    <style>
        /* ========== PDP v2 — layout matching the image ========== */
        .pdp-top {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(0, 1fr);
            gap: 44px;
            align-items: start
        }

        @media(max-width:1024px) {
            .pdp-top {
                grid-template-columns: 1fr;
                gap: 28px
            }
        }

        .pdp-main {
            border-radius: 18px;
            overflow: hidden;
            background: #efe9dd;
            aspect-ratio: 1/1
        }

        .pdp-thumbs {
            margin-top: 14px;
            gap: 10px
        }

        .pdp-thumbs .product-gallery-item {
            border-radius: 12px;
            border: 1px solid var(--line);
            overflow: hidden
        }

        .pdp-thumbs .product-gallery-item.active {
            border-color: var(--brass-d);
            box-shadow: 0 0 0 1px var(--brass-d)
        }

        .pdp-info {
            padding-top: 4px
        }

        .pdp-info .pdp-cat {
            font-size: 11.5px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--ink-50);
            font-weight: 600;
            margin-bottom: 10px
        }

        .pdp-info h1 {
            font-family: 'Fraunces', Georgia, serif;
            font-size: 44px;
            line-height: 1.05;
            font-weight: 500;
            color: var(--ink);
            margin: 0 0 18px;
            letter-spacing: -.01em
        }

        @media(max-width:640px) {
            .pdp-info h1 {
                font-size: 32px
            }
        }

        .pdp-price-rating-wrap {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin: 0 0 14px
        }

        .pdp-price {
            display: inline-flex;
            align-items: baseline;
            gap: 12px;
            margin: 0
        }

        .pdp-price #display-price {
            font-family: 'Fraunces', Georgia, serif;
            font-size: 30px;
            font-weight: 600;
            color: var(--brass-d);
            line-height: 1
        }

        .pdp-mrp {
            font-size: 16px;
            color: var(--ink-30);
            text-decoration: line-through;
            font-weight: 500
        }

        .pdp-off {
            display: inline-flex;
            align-items: center;
            height: 26px;
            padding: 0 12px;
            border-radius: 999px;
            background: #fde8e6;
            color: #c0392b;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .04em;
            border: 1px solid #f5cfcb
        }

        .pdp-info .pdp-ratings {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 0 auto
        }

        .pdp-info .pdp-ratings .ratings-text {
            font-weight: 600;
            font-size: 15px;
            color: var(--ink-70);
            border: none;
            padding: 0
        }

        .btn-share-icon {
            width: 40px;
            height: 40px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: transparent;
            color: var(--ink-70);
            display: inline-grid;
            place-items: center;
            cursor: pointer;
            transition: border-color .2s ease, color .2s ease
        }

        .btn-share-icon:hover {
            border-color: var(--brass-d);
            color: var(--brass-d)
        }

        .btn-share-icon svg {
            width: 18px;
            height: 18px
        }

        .pdp-short {
            font-size: 15px;
            line-height: 1.55;
            color: var(--ink-70);
            margin: 0 0 22px
        }

        .pdp-trust {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            padding: 14px 18px;
            border-radius: 14px;
            background: #f7f1e5;
            border: 1px solid var(--line);
            margin-bottom: 26px
        }

        .pdp-trust>div {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 10px;
            border-right: 1px solid rgba(0, 0, 0, .06)
        }

        .pdp-trust>div:last-child {
            border-right: none
        }

        .pdp-trust .ic {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #efe4cd;
            display: grid;
            place-items: center;
            color: var(--brass-d);
            flex: 0 0 auto
        }

        .pdp-trust .ic svg {
            width: 18px;
            height: 18px
        }

        .pdp-trust b {
            display: block;
            font-size: 13px;
            color: var(--ink);
            font-weight: 600;
            line-height: 1.1
        }

        .pdp-trust small {
            display: block;
            font-size: 11.5px;
            color: var(--ink-50);
            margin-top: 3px
        }

        @media(max-width:720px) {
            .pdp-trust {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
                padding: 14px
            }

            .pdp-trust>div {
                border-right: none;
                padding: 0
            }
        }

        .pdp-row {
            margin-bottom: 22px
        }

        .pdp-row>label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--ink-70);
            margin-bottom: 12px
        }

        .pdp-color-ar {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 22px;
            align-items: end;
            margin-bottom: 22px
        }

        .pdp-swatches {
            display: flex;
            gap: 12px;
            flex-wrap: wrap
        }

        .pdp-swatches .color-swatch {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-block;
            position: relative;
            border: 2px solid #fff;
            box-shadow: 0 0 0 1px var(--line);
            cursor: pointer;
            transition: box-shadow .18s ease, transform .18s ease
        }

        .pdp-swatches .color-swatch:hover {
            transform: translateY(-1px)
        }

        .pdp-swatches .color-swatch.active {
            box-shadow: 0 0 0 2px var(--brass-d)
        }

        .pdp-swatches .color-swatch .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0)
        }

        .pdp-ar-card {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 12px;
            background: linear-gradient(180deg, #faf3e2, #f4ead0);
            border: 1px solid #e8d9b0;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            min-width: 220px;
            transition: transform .18s ease, box-shadow .2s ease
        }

        .pdp-ar-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(138, 95, 33, .12);
            text-decoration: none;
            color: inherit
        }

        .pdp-ar-card .ar-ic {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .55);
            display: grid;
            place-items: center;
            color: var(--brass-d);
            flex: 0 0 auto
        }

        .pdp-ar-card .ar-ic svg {
            width: 22px;
            height: 22px
        }

        .pdp-ar-card .ar-txt {
            display: block;
            line-height: 1.25
        }

        .pdp-ar-card .ar-txt b {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--ink)
        }

        .pdp-ar-card .ar-txt small {
            display: block;
            font-size: 11.5px;
            color: var(--ink-50);
            margin-top: 2px
        }

        .pdp-ar-card .ar-arrow {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--brass-d);
            display: grid;
            place-items: center;
            color: #fff;
            flex: 0 0 auto
        }

        .pdp-ar-card .ar-arrow svg {
            width: 14px;
            height: 14px
        }

        @media(max-width:640px) {
            .pdp-color-ar {
                grid-template-columns: 1fr
            }

            .pdp-ar-card {
                width: 100%
            }
        }

        .pdp-size-row {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap
        }

        .pdp-size-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            flex: 1 1 auto
        }

        .pdp-size-chip {
            height: 44px;
            padding: 0 20px;
            border: 1px solid var(--line-2);
            border-radius: 10px;
            background: #fff;
            color: var(--ink);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: border-color .18s ease, color .18s ease
        }

        .pdp-size-chip:hover {
            border-color: var(--brass)
        }

        .pdp-size-chip.active {
            border-color: var(--brass-d);
            color: var(--brass-d);
            font-weight: 600;
            box-shadow: 0 0 0 1px var(--brass-d)
        }

        .pdp-size-chip[disabled],
        .pdp-size-chip.pc-off {
            opacity: 0.5;
            cursor: not-allowed !important;
            background: #f9f9f9;
            border-color: var(--line-2);
            color: var(--ink-50);
        }

        .size-guide {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--ink-50);
            font-size: 13px;
            text-decoration: none
        }

        .size-guide:hover {
            color: var(--brass-d);
            text-decoration: none
        }

        select#size.pdp-hidden {
            position: absolute;
            opacity: 0;
            pointer-events: none;
            height: 0
        }

        .pdp-qty-stock {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: end;
            margin-bottom: 22px
        }

        .pdp-qty {
            padding: 0;
            background: transparent;
            border: 0;
            height: auto
        }

        .pdp-qty>label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--ink-70);
            margin-bottom: 12px
        }

        .pdp-stepper {
            display: inline-flex;
            align-items: center;
            height: 50px;
            border: 1px solid var(--line-2);
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
            width: 160px
        }

        .pdp-stepper button {
            width: 50px;
            height: 100%;
            border: 0;
            background: transparent;
            font-size: 20px;
            line-height: 1;
            color: var(--ink-70);
            cursor: pointer;
            transition: background .18s ease
        }

        .pdp-stepper button:hover {
            background: #f6f0e5;
            color: var(--ink)
        }

        .pdp-stepper input {
            flex: 1;
            height: 100%;
            border: 0;
            background: transparent;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            color: var(--ink);
            -moz-appearance: textfield;
            outline: none
        }

        .pdp-stepper input::-webkit-outer-spin-button,
        .pdp-stepper input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0
        }

        .pdp-stock {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            height: 50px;
            padding: 0 16px;
            border-radius: 10px;
            background: #fbeeec;
            border: 1px solid #f5cfcb;
            color: #c0392b;
            line-height: 1.15
        }

        .pdp-stock .ic {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #fff;
            display: grid;
            place-items: center;
            flex: 0 0 auto
        }

        .pdp-stock .ic svg {
            width: 14px;
            height: 14px
        }

        .pdp-stock b {
            display: block;
            font-size: 13.5px;
            font-weight: 700
        }

        .pdp-stock small {
            display: block;
            font-size: 11.5px;
            color: #b0422f
        }

        .pdp-stock.is-in {
            background: #e9f5ee;
            border-color: #c9e4d3;
            color: #2f7d52
        }

        .pdp-stock.is-in small {
            color: #2f7d52;
            opacity: .8
        }

        @media(max-width:520px) {
            .pdp-qty-stock {
                grid-template-columns: 1fr
            }

            .pdp-stock {
                justify-content: center
            }
        }

        .pdp-actions {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 14px;
            /* margin-bottom: 26px */
        }

        .pdp-actions #add-to-cart-btn {
            height: 58px;
            border-radius: 999px;
            background: #131a2b;
            border: 0;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: background .2s ease, transform .1s ease;
            cursor: pointer
        }

        .pdp-actions #add-to-cart-btn:hover {
            background: #1c2440
        }

        .pdp-actions #add-to-cart-btn:active {
            transform: translateY(1px)
        }

        .pdp-actions #add-to-cart-btn svg {
            width: 20px;
            height: 20px
        }

        .pdp-actions .btn-wishlist-pdp {
            height: 58px;
            border-radius: 999px;
            border: 1.5px solid #c0392b;
            background: #fff;
            color: #c0392b;
            font-size: 15px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            transition: background .2s ease
        }

        .pdp-actions .btn-wishlist-pdp:hover {
            background: #fdf1ef;
            color: #c0392b;
            text-decoration: none
        }

        .pdp-actions .btn-wishlist-pdp svg {
            width: 20px;
            height: 20px
        }

        .pdp-actions .btn-wishlist-pdp.on {
            background: #c0392b;
            color: #fff
        }

        .pdp-actions .btn-wishlist-pdp.on svg {
            fill: #fff
        }

        /* ============================================================
               ✨ BUTTON ANIMATIONS
               ============================================================ */

        /* --- base for both buttons: ready for ripple + shine --- */
        .pdp-actions #add-to-cart-btn,
        .pdp-actions .btn-wishlist-pdp {
            position: relative;
            overflow: hidden;
            isolation: isolate;
            -webkit-tap-highlight-color: transparent;
            transition:
                background .25s cubic-bezier(.22, .8, .28, 1),
                color .25s ease,
                border-color .25s ease,
                box-shadow .3s cubic-bezier(.22, .8, .28, 1),
                transform .18s cubic-bezier(.22, .8, .28, 1);
        }

        .pdp-actions #add-to-cart-btn>*,
        .pdp-actions .btn-wishlist-pdp>* {
            position: relative;
            z-index: 2;
        }

        /* --- slight lift on hover + deeper shadow --- */
        .pdp-actions #add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 26px rgba(19, 26, 43, .28);
        }

        .pdp-actions .btn-wishlist-pdp:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(192, 57, 43, .18);
        }

        .pdp-actions #add-to-cart-btn:active,
        .pdp-actions .btn-wishlist-pdp:active {
            transform: translateY(0) scale(.985);
            transition-duration: .08s;
        }

        /* --- shine sweep (adds a shine effect on hover) --- */
        .pdp-actions #add-to-cart-btn::before,
        .pdp-actions .btn-wishlist-pdp::before {
            content: '';
            position: absolute;
            top: 0;
            left: -75%;
            width: 50%;
            height: 100%;
            z-index: 1;
            transform: skewX(-22deg);
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, .28),
                    transparent);
            transition: left .65s cubic-bezier(.22, .8, .28, 1);
        }

        .pdp-actions .btn-wishlist-pdp::before {
            background: linear-gradient(90deg,
                    transparent,
                    rgba(192, 57, 43, .12),
                    transparent);
        }

        .pdp-actions #add-to-cart-btn:hover::before,
        .pdp-actions .btn-wishlist-pdp:hover::before {
            left: 125%;
        }

        /* --- icon's own micro-motion --- */
        .pdp-actions #add-to-cart-btn svg {
            transition: transform .32s cubic-bezier(.34, 1.56, .64, 1);
        }

        .pdp-actions #add-to-cart-btn:hover svg {
            transform: translateY(-2px) rotate(-6deg);
        }

        .pdp-actions .btn-wishlist-pdp svg {
            transition: transform .3s cubic-bezier(.34, 1.56, .64, 1), fill .25s ease;
        }

        .pdp-actions .btn-wishlist-pdp:hover svg {
            transform: scale(1.15);
        }

        /* --- heart pops as soon as wishlist is ON --- */
        .pdp-actions .btn-wishlist-pdp.on svg {
            animation: pdpHeartPop .45s cubic-bezier(.34, 1.56, .64, 1);
        }

        @keyframes pdpHeartPop {
            0% {
                transform: scale(1);
            }

            40% {
                transform: scale(1.4);
            }

            70% {
                transform: scale(.92);
            }

            100% {
                transform: scale(1);
            }
        }

        /* --- click ripple (span is injected via JS) --- */
        .pdp-ripple {
            position: absolute;
            z-index: 1;
            border-radius: 50%;
            transform: scale(0);
            pointer-events: none;
            background: rgba(255, 255, 255, .35);
            animation: pdpRipple .6s cubic-bezier(.22, .8, .28, 1) forwards;
        }

        .btn-wishlist-pdp .pdp-ripple {
            background: rgba(192, 57, 43, .18);
        }

        .btn-wishlist-pdp.on .pdp-ripple {
            background: rgba(255, 255, 255, .3);
        }

        @keyframes pdpRipple {
            to {
                transform: scale(2.6);
                opacity: 0;
            }
        }

        /* --- "Added" success state (class applied by JS) --- */
        .pdp-actions #add-to-cart-btn.is-added {
            background: #2f7d52 !important;
            box-shadow: 0 10px 24px rgba(47, 125, 82, .3);
        }

        .pdp-actions #add-to-cart-btn.is-added svg {
            transform: scale(1.1);
        }

        /* --- loading spinner state --- */
        .pdp-actions #add-to-cart-btn.is-loading {
            cursor: wait;
            opacity: .85;
        }

        .pdp-actions #add-to-cart-btn.is-loading svg {
            animation: pdpSpin .7s linear infinite;
        }

        @keyframes pdpSpin {
            to {
                transform: rotate(360deg);
            }
        }

        /* --- when the sticky bar first appears, slide it up from below --- */
        @media (min-width: 769px) {
            .pdp-actions:not(.is-parked) {
                animation: pdpBarRise .45s cubic-bezier(.22, .8, .28, 1);
            }
        }

        @keyframes pdpBarRise {
            from {
                transform: translateY(12px);
                opacity: .6;
            }

            to {
                transform: none;
                opacity: 1;
            }
        }

        /* --- for those who prefer reduced motion --- */
        @media (prefers-reduced-motion: reduce) {

            .pdp-actions #add-to-cart-btn,
            .pdp-actions .btn-wishlist-pdp,
            .pdp-actions #add-to-cart-btn svg,
            .pdp-actions .btn-wishlist-pdp svg,
            .pdp-actions:not(.is-parked) {
                animation: none !important;
                transition-duration: .01ms !important;
                transform: none !important;
            }

            .pdp-actions #add-to-cart-btn::before,
            .pdp-actions .btn-wishlist-pdp::before {
                display: none;
            }
        }

        /* ============================================================
               DESKTOP - sticky: scrolls along, stops at its place
               ============================================================ */
        @media (min-width: 769px) {
            .pdp-actions {
                position: sticky;
                bottom: 24px;
                z-index: 20;
                background: var(--paper, #fdfaf4);
                padding: 14px;
                margin-left: -14px;
                margin-right: -14px;
                border-radius: 16px;
                box-shadow: 0 -2px 24px rgba(32, 38, 58, .07);
                transition: box-shadow .25s ease, background .25s ease;
            }

            /* reached its original place — flatten it */
            .pdp-actions.is-parked {
                box-shadow: none;
                background: transparent;
            }
        }

        /* ============================================================
               MOBILE — ek row: full-width Add to bag + wishlist icon
               ============================================================ */
        @media (max-width: 768px) {
            .pdp-actions {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 900;
                display: grid;
                grid-template-columns: 1fr 58px;
                gap: 10px;
                margin: 0;
                padding: 10px 14px calc(10px + env(safe-area-inset-bottom));
                background: var(--paper, #fdfaf4);
                border-top: 1px solid var(--line);
                box-shadow: 0 -4px 20px rgba(32, 38, 58, .1);
            }

            .pdp-actions #add-to-cart-btn {
                height: 52px;
                font-size: 15px;
            }

            /* wishlist sirf icon */
            .pdp-actions .btn-wishlist-pdp {
                width: 58px;
                height: 52px;
                padding: 0;
                border-radius: 999px;
                gap: 0;
            }

            .pdp-actions .btn-wishlist-pdp .btn-label {
                display: none;
            }

            .pdp-actions .btn-wishlist-pdp svg {
                width: 22px;
                height: 22px;
            }

            /* space at the bottom — otherwise the bar covers the content */
            .pdp-page,
            .pdp {
                padding-bottom: 84px;
            }
        }

        .pdp-info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            padding: 18px 4px 8px 4px !important;
            background: #f7f1e5;
            border: 1px solid var(--line);
            border-radius: 14px
        }

        .pdp-info-grid>div {
            display: flex;
            gap: 12px;
            padding: 0 14px;
            border-right: 1px solid rgba(0, 0, 0, .06)
        }

        .pdp-info-grid>div:last-child {
            border-right: none
        }

        .pdp-info-grid .ic {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #efe4cd;
            display: grid;
            place-items: center;
            color: var(--brass-d);
            flex: 0 0 auto
        }

        .pdp-info-grid .ic svg {
            width: 18px;
            height: 18px
        }

        .pdp-info-grid b {
            display: block;
            font-size: 13px;
            color: var(--ink);
            font-weight: 600;
            line-height: 1.1;
            margin-bottom: 4px
        }

        .pdp-info-grid p {
            margin: 0;
            font-size: 12px;
            color: var(--ink-50);
            line-height: 1.4
        }

        @media(max-width:900px) {
            .pdp-info-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px
            }

            .pdp-info-grid>div {
                border-right: none;
                padding: 0
            }
        }

        .pdp-gallery [data-wv-open] {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 14px 18px;
            border-radius: 14px;
            background: #f7f1e5;
            border: 1px solid var(--line);
            text-align: left;
            height: auto;
            margin-top: 14px;
            color: var(--ink);
            justify-content: flex-start
        }

        .pdp-gallery [data-wv-open]:hover {
            border-color: var(--brass-d);
            background: #f2e9d3
        }

        .pdp-gallery [data-wv-open] svg {
            flex: 0 0 auto
        }

        .pdp-gallery [data-wv-open]::after {
            content: '›';
            margin-left: auto;
            color: var(--brass-d);
            font-size: 22px;
            line-height: 1
        }
    </style>
    @php
        $reviewCount = $product->reviews->count();
        $avgRating = $product->reviews->avg('rating') ?: 0;
        $ratingPercent = ($avgRating / 5) * 100;
        $uniqueColors = $product->variations->pluck('color')->filter()->unique();
        $uniqueSizes = $product->variations->pluck('size')->filter()->unique()->values();
        $defaultVar = $product->variations->first();
        $mainImgPath =
            $defaultVar && $defaultVar->image
                ? asset('public/uploads/products/variations/' . $defaultVar->image)
                : asset('public/uploads/products/no-image.jpg');
        $totalImages =
            ($defaultVar && $defaultVar->image ? 1 : 0) + (isset($product->images) ? $product->images->count() : 0);
        $inWishlist = in_array($product->id, $wishlistProductIds ?? []);
    @endphp

    <section class="page active pdp">
        <div class="wrap">
            {{-- Breadcrumb + prev/next (real routes preserved) --}}
            <div style="display:flex;">
                <div class="crumbs" style="margin:0">
                    <a href="{{ url('/') }}">Home</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                    <a href="{{ route('product.categories_list') }}">Categories</a><svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                    @if ($product->category)
                        <a
                            href="{{ route('product.category', $product->category->slug) }}">{{ $product->category->name }}</a><svg
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    @endif
                    <span class="cur">{{ $product->name }}</span>
                </div>
                <nav class="pdp-pager">
                    @if ($prevProduct)
                        <a href="{{ route('product.details', $prevProduct->slug) }}" title="Previous"><svg
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m15 18-6-6 6-6" />
                            </svg></a>
                    @else<a class="disabled" href="javascript:void(0)"><svg viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="m15 18-6-6 6-6" />
                            </svg></a>
                    @endif
                    @if ($nextProduct)
                        <a href="{{ route('product.details', $nextProduct->slug) }}" title="Next"><svg viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m9 18 6-6-6-6" />
                            </svg></a>
                    @else<a class="disabled" href="javascript:void(0)"><svg viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="m9 18 6-6-6-6" />
                            </svg></a>
                    @endif
                </nav>
            </div>

            <div class="pdp-top">
                {{-- ===== GALLERY (JS hooks preserved) ===== --}}
                <div class="pdp-gallery">
                    <figure class="pdp-main">
                        <img id="product-zoom" src="{{ $mainImgPath }}" data-zoom-image="{{ $mainImgPath }}"
                            alt="{{ $product->name }}">

                        @if ($totalImages > 1)
                            <button type="button" id="pdp-prev" class="pdp-nav pdp-nav-prev" aria-label="Previous image">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                            </button>
                            <button type="button" id="pdp-next" class="pdp-nav pdp-nav-next" aria-label="Next image">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </button>
                        @endif

                        <a href="#" id="btn-product-gallery" class="btn-product-gallery" aria-label="Zoom image">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="7" />
                                <path d="M11 8v6M8 11h6M20 20l-3.5-3.5" />
                            </svg>
                            <span class="bpg-label">Zoom</span>
                        </a>

                        {{-- hint shown on first visit --}}
                        <div class="pdp-zoom-hint" id="pdpZoomHint">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="11" cy="11" r="7" />
                                <path d="M11 8v6M8 11h6M20 20l-3.5-3.5" />
                            </svg>
                            Tap to zoom
                        </div>
                    </figure>
                    <div id="product-zoom-gallery" class="pdp-thumbs"
                        style="{{ $totalImages <= 1 ? 'display:none;' : '' }}">
                        @if ($defaultVar && $defaultVar->image)
                            @php
                                $vInfo = pathinfo($defaultVar->image);
                                $vSideThumb = $vInfo['filename'] . '_side.' . $vInfo['extension'];
                            @endphp
                            <a class="product-gallery-item active" href="#"
                                data-image="{{ asset('public/uploads/products/variations/' . $defaultVar->image) }}"
                                data-zoom-image="{{ asset('public/uploads/products/variations/' . $defaultVar->image) }}">
                                <img src="{{ asset('public/uploads/products/variations/side/' . $vSideThumb) }}"
                                    alt="product side" onerror="this.src='{{ $mainImgPath }}'">
                            </a>
                        @endif
                        @if (isset($product->images) && $product->images->count() > 0)
                            @foreach ($product->images as $img)
                                <a class="product-gallery-item" href="#"
                                    data-image="{{ asset('public/uploads/products/gallery/' . $img->image) }}"
                                    data-zoom-image="{{ asset('public/uploads/products/gallery/' . $img->image) }}">
                                    <img src="{{ asset('public/uploads/products/gallery/side/' . $img->side_image) }}"
                                        alt="gallery"
                                        onerror="this.src='{{ asset('public/uploads/products/gallery/' . $img->image) }}'">
                                </a>
                            @endforeach
                        @endif
                    </div>

                    {{-- <button type="button" class="btn btn-ghost" data-wv-open style="width:100%;margin-top:14px">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M3 21V8l9-5 9 5v13" />
                            <rect x="9" y="11" width="6" height="5" rx="1" />
                        </svg>
                        See it on your wall
                    </button> --}}
                </div>

                {{-- ===== INFO + ADD TO CART (form/action/fields preserved) ===== --}}
                <div class="pdp-info">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" data-ajax-cart>
                        @csrf
                        <input type="hidden" name="color" id="selected-color"
                            value="{{ $product->variations->pluck('color')->filter()->first() ?? '' }}">

                        @if ($product->category)
                            <div class="pdp-cat">{{ $product->category->name }}</div>
                        @endif
                        <h1>{{ $product->name }}</h1>

                        {{-- PRICE + RATING + SHARE --}}
                        <div class="pdp-price-rating-wrap">
                            <div class="pdp-price">
                                <span id="display-price">
                                    @if ($product->variations->count() > 0)
                                        ₹{{ number_format($product->variations->min('price'), 2) }}
                                    @else
                                        ₹{{ number_format($product->price, 2) }}
                                    @endif
                                </span>
                                @if (isset($product->mrp) && $product->mrp > $product->price)
                                    <span class="pdp-mrp">₹{{ number_format($product->mrp, 2) }}</span>
                                    <span
                                        class="pdp-off">{{ round((($product->mrp - $product->price) / $product->mrp) * 100) }}%
                                        OFF</span>
                                @endif
                            </div>

                            <div class="pdp-ratings">
                                <span class="ratings"><span class="ratings-val"
                                        style="width: {{ $ratingPercent }}%;"></span></span>
                                <span class="ratings-text">{{ number_format($avgRating, 1) }}
                                    ({{ $reviewCount }})</span>
                            </div>

                            <button type="button" class="btn-share-icon" title="Share"
                                onclick="if(navigator.share){navigator.share({title:document.title,url:location.href})}else{navigator.clipboard.writeText(location.href)}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8" />
                                    <polyline points="16 6 12 2 8 6" />
                                    <line x1="12" y1="2" x2="12" y2="15" />
                                </svg>
                            </button>
                        </div>

                        <p class="pdp-short">
                            {{ $product->short_description ?? \Illuminate\Support\Str::limit(strip_tags($product->description ?? ''), 180) ?: 'A one-of-a-kind handmade piece, crafted with care.' }}
                        </p>

                        {{-- TRUST STRIP --}}
                        <div class="pdp-trust">
                            <div>
                                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                    </svg></span>
                                <div><b>Handmade</b><small>With Love</small></div>
                            </div>
                            <div>
                                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                        <path d="m9 12 2 2 4-4" />
                                    </svg></span>
                                <div><b>Authentic</b><small>{{ $product->category->name ?? 'Handmade Art' }}</small></div>
                            </div>
                            <div>
                                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                        <path d="M3.27 6.96 12 12.01l8.73-5.05M12 22.08V12" />
                                    </svg></span>
                                <div><b>Secure</b><small>Packaging</small></div>
                            </div>
                            <div>
                                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8">
                                        <rect x="1" y="3" width="15" height="13" />
                                        <path d="M16 8h4l3 3v5h-7z" />
                                        <circle cx="5.5" cy="18.5" r="2.5" />
                                        <circle cx="18.5" cy="18.5" r="2.5" />
                                    </svg></span>
                                <div><b>Free Shipping</b><small>Above ₹999</small></div>
                            </div>
                        </div>

                        {{-- COLOR + AR CARD --}}
                        @if ($uniqueColors->count() > 0)
                            <div class="pdp-color-ar">
                                <div class="pdp-row" style="margin:0">
                                    <label>Color</label>
                                    <div class="pdp-swatches">
                                        @foreach ($uniqueColors as $color)
                                            <a href="#" class="color-swatch {{ $loop->first ? 'active' : '' }}"
                                                style="background: {{ strtolower($color) }};"
                                                title="{{ $color }}" data-color="{{ $color }}">
                                                <span class="sr-only">{{ $color }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                                <a href="#" class="pdp-ar-card" data-wv-open>
                                    <span class="ar-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path d="M3 21V8l9-5 9 5v13" />
                                            <rect x="9" y="11" width="6" height="5" rx="1" />
                                        </svg></span>
                                    <span class="ar-txt"><b>See it on your wall</b><small>Try it in your space with AR
                                            preview</small></span>
                                    <span class="ar-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path d="m9 18 6-6-6-6" />
                                        </svg></span>
                                </a>
                            </div>
                        @endif

                        {{-- SIZE CHIPS --}}
                        <div class="pdp-row">
                            <label>Size</label>
                            <div class="pdp-size-row">
                                <div class="pdp-size-chips" id="pdpSizeChips">
                                    @if (count($uniqueSizes) > 0)
                                        @foreach ($uniqueSizes as $i => $sz)
                                            <button type="button" class="pdp-size-chip {{ $i === 0 ? 'active' : '' }}"
                                                data-size="{{ $sz }}">{{ strtoupper($sz) }}</button>
                                        @endforeach
                                    @else
                                        @foreach (['s' => 'Small', 'm' => 'Medium', 'l' => 'Large', 'xl' => 'Extra Large'] as $val => $lbl)
                                            <button type="button"
                                                class="pdp-size-chip {{ $loop->first ? 'active' : '' }}"
                                                data-size="{{ $val }}">{{ $lbl }}</button>
                                        @endforeach
                                    @endif
                                </div>

                                <select name="size" id="size" class="pdp-hidden" required>
                                    <option value="" disabled>Select a size</option>
                                    @if (count($uniqueSizes) > 0)
                                        @foreach ($uniqueSizes as $i => $sz)
                                            <option value="{{ $sz }}" {{ $i === 0 ? 'selected' : '' }}>
                                                {{ strtoupper($sz) }}</option>
                                        @endforeach
                                    @else
                                        <option value="s" selected>Small</option>
                                        <option value="m">Medium</option>
                                        <option value="l">Large</option>
                                        <option value="xl">Extra Large</option>
                                    @endif
                                </select>

                                <a href="#" class="size-guide">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        style="width:15px;height:15px">
                                        <path d="M3 6h18M3 12h18M3 18h18" />
                                    </svg>
                                    Size guide
                                </a>
                            </div>
                        </div>

                        {{-- QTY + STOCK --}}
                        <div class="pdp-qty-stock">
                            <div class="pdp-qty">
                                <label for="qty">Qty</label>
                                <div class="pdp-stepper">
                                    <button type="button" data-qty-step="-1" aria-label="Decrease">−</button>
                                    <input type="number" name="quantity" id="qty" value="1" min="1"
                                        max="10" step="1" required>
                                    <button type="button" data-qty-step="1" aria-label="Increase">+</button>
                                </div>
                            </div>

                            @php $inStock = property_exists($product, 'stock') ? ($product->stock > 0) : true; @endphp
                            <div class="pdp-stock {{ $inStock ? 'is-in' : '' }}">
                                <span class="ic">
                                    @if ($inStock)
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path d="m5 12 5 5L20 7" />
                                        </svg>
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path d="M18 6 6 18M6 6l12 12" />
                                        </svg>
                                    @endif
                                </span>
                                <div>
                                    <b>{{ $inStock ? 'In stock' : 'Out of stock' }}</b>
                                    <small>{{ $inStock ? 'Ships in 24 hours' : 'Currently unavailable' }}</small>
                                </div>
                            </div>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="pdp-actions">
                            <button type="submit" class="btn btn-primary btn-lg" id="add-to-cart-btn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                                    <path d="M3 6h18M16 10a4 4 0 0 1-8 0" />
                                </svg>
                                <span class="btn-label">Add to bag</span>
                            </button>

                            <a href="{{ route('wishlist.toggle', $product->id) }}" data-id="{{ $product->id }}"
                                class="btn-wishlist-pdp wish-btn {{ $inWishlist ? 'on' : '' }}"
                                title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                <svg viewBox="0 0 24 24" fill="{{ $inWishlist ? 'currentColor' : 'none' }}"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path
                                        d="M12 20s-7-4.3-7-9.3A3.7 3.7 0 0 1 12 8a3.7 3.7 0 0 1 7 2.7C19 15.7 12 20 12 20z" />
                                </svg>
                                <span class="btn-label">Add to wishlist</span>
                            </a>
                        </div>

                        {{-- BOTTOM INFO GRID --}}
                        <div class="pdp-info-grid">
                            <div>
                                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8">
                                        <path
                                            d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z" />
                                        <path d="M2 21c0-3 1.85-5.36 5.08-6" />
                                    </svg></span>
                                <div><b>Material</b>
                                    <p>{{ $product->material ?? 'Natural Colors on Paper' }}</p>
                                </div>
                            </div>
                            <div>
                                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8">
                                        <rect x="3" y="3" width="18" height="18" rx="2" />
                                        <path d="M3 9h18M9 3v18" />
                                    </svg></span>
                                <div><b>Dimensions</b>
                                    <p>{{ $product->dimensions ?? 'Available in multiple sizes' }}</p>
                                </div>
                            </div>
                            <div>
                                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8">
                                        <circle cx="12" cy="12" r="4" />
                                        <path
                                            d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
                                    </svg></span>
                                <div><b>Care Instructions</b>
                                    <p>Keep away from direct sunlight</p>
                                </div>
                            </div>
                            <div>
                                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8">
                                        <rect x="1" y="3" width="15" height="13" />
                                        <path d="M16 8h4l3 3v5h-7z" />
                                        <circle cx="5.5" cy="18.5" r="2.5" />
                                        <circle cx="18.5" cy="18.5" r="2.5" />
                                    </svg></span>
                                <div><b>Delivery</b>
                                    <p>3–5 business days</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- =========================================================
                 EDITORIAL STORY - replacing the old 4 bootstrap tabs.
                 Everything on one page, readable as you scroll.
                 Old tab anchors (#product-desc-tab etc) ids are
                 forms, so that old links do not break.
                 CSS: arthubly-product.css → section D
                 ========================================================= --}}
            @php
                $specs = [];

                $specs[] = [
                    'ic' => 'brush',
                    'label' => 'Medium',
                    'value' => $product->material ?? 'Natural colours on paper',
                ];
                $specs[] = [
                    'ic' => 'ruler',
                    'label' => 'Size',
                    'value' =>
                        $product->dimensions ??
                        ($uniqueSizes->count() ? $uniqueSizes->implode(' · ') : 'One standard size'),
                ];

                if ($product->category) {
                    $specs[] = ['ic' => 'tag', 'label' => 'Craft', 'value' => $product->category->name];
                }
                if ($uniqueColors->count()) {
                    $specs[] = ['ic' => 'drop', 'label' => 'Finish', 'value' => $uniqueColors->implode(', ')];
                }

                $specs[] = ['ic' => 'shield', 'label' => 'Made by', 'value' => 'An independent artisan, by hand'];
                $specs[] = ['ic' => 'box', 'label' => 'Packaging', 'value' => 'Padded, protective, plastic-free'];
                $specs[] = ['ic' => 'truck', 'label' => 'Delivery', 'value' => 'Ships in 3–5 business days'];
                $specs[] = ['ic' => 'back', 'label' => 'Returns', 'value' => '7 days from delivery'];

                $process = [
                    ['Sketch', 'The first outline is drawn by hand.'],
                    ['Base layer', 'Background and base colours are applied.'],
                    ['Details', 'Depth and details are added layer by layer.'],
                    ['Highlights', 'Highlights give life to the piece.'],
                    ['Finishing', 'Final touches and protective coating.'],
                    ['Checked', 'Inspected against Handmade Verified standards.'],
                ];

                /* --------------------------------------------------------------
                   IMAGE POOL — for process tiles and detail strip.
                   Variation images first, then gallery images.
                   If nothing is found, fall back to the main image.
                   -------------------------------------------------------------- */
                $storyShots = [];
                foreach ($product->variations as $v) {
                    if (!empty($v->image)) {
                        $storyShots[] = asset('public/uploads/products/variations/' . $v->image);
                    }
                }
                if (isset($product->images)) {
                    foreach ($product->images as $gi) {
                        if (!empty($gi->image)) {
                            $storyShots[] = asset('public/uploads/products/gallery/' . $gi->image);
                        }
                    }
                }
                $storyShots = array_values(array_unique($storyShots));
                if (empty($storyShots)) {
                    $storyShots = [$mainImgPath];
                }
                $shotAt = fn($i) => $storyShots[$i % count($storyShots)];

                $care = [
                    ['sun', 'Keep away from direct sunlight'],
                    ['cloth', 'Clean with a soft dry cloth'],
                    ['drop', 'Avoid moisture and high humidity'],
                    ['hand', 'Handle with clean, dry hands'],
                    ['box', 'Store flat in a cool, dry place'],
                ];
            @endphp

            <div class="pdp-story">

                {{-- ---------- SECTION NAV (sticky) ---------- --}}
                <nav class="ps-nav" aria-label="Product sections">
                    <a href="#product-desc-tab" class="is-on">The artwork</a>
                    <a href="#product-info-tab">Specifications</a>
                    <a href="#product-shipping-tab">Process &amp; care</a>
                    <a href="#product-review-tab">Reviews ({{ $reviewCount }})</a>
                </nav>

                {{-- ---------- 1. THE ARTWORK + SPECS ---------- --}}
                <section class="ps-block ps-artwork" id="product-desc-tab">
                    <div class="ps-artwork-lhs">
                        <span class="ps-eyebrow">The artwork</span>
                        <h2 class="ps-title">{{ $product->name }}</h2>

                        <div class="ps-prose">
                            {!! $product->description ??
                                '<p>A beautifully handcrafted piece, made by an independent artisan. Because every piece is made by hand, small variations in colour, texture and finish are natural — they are what make yours one of a kind.</p>' !!}
                        </div>

                        {{-- what's special — always visible, even if description
                             is short. Generated from data, not hardcoded. --}}
                        <ul class="ps-points">
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m5 13 4 4L19 7" />
                                </svg>
                                Completely handmade — no prints, no machines
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m5 13 4 4L19 7" />
                                </svg>
                                {{ $product->material ?? 'Natural colours on paper' }}, handpicked by the artisan
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m5 13 4 4L19 7" />
                                </svg>
                                Dispatched only after passing Handmade Verified standards
                            </li>
                        </ul>

                        {{-- detail shots — left column empty space is useful now --}}
                        @if (count($storyShots) > 1)
                            <div class="ps-shots">
                                <span class="ps-shots-label">Piece in detail</span>
                                <div class="ps-shots-row">
                                    @foreach (array_slice($storyShots, 0, 4) as $sh)
                                        <img src="{{ $sh }}" alt="{{ $product->name }} detail" loading="lazy"
                                            onerror="this.remove()">
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <p class="ps-sign">Handmade with patience</p>

                        <div class="ps-badges">
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                    <path
                                        d="M20.8 6.6a5 5 0 0 0-7.1 0L12 8.3l-1.7-1.7a5 5 0 1 0-7.1 7.1L12 22l8.8-8.3a5 5 0 0 0 0-7.1z" />
                                </svg>
                                Handmade<br>with love
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                    <path d="m12 3 2.6 5.3 5.9.9-4.2 4.1 1 5.8L12 16.9l-5.3 2.2 1-5.8L3.5 9.2l5.9-.9z" />
                                </svg>
                                Premium<br>quality
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                    <path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z" />
                                    <path d="m9 12 2 2 4-4" />
                                </svg>
                                Secure<br>packaging
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                    <path d="M9 14 4 9l5-5" />
                                    <path d="M4 9h11a5 5 0 0 1 0 10h-3" />
                                </svg>
                                Easy<br>returns
                            </span>
                        </div>
                    </div>

                    {{-- ---------- 2. SPECIFICATIONS ---------- --}}
                    <div class="ps-artwork-rhs" id="product-info-tab">
                        <h3 class="ps-h3">Materials &amp; specifications</h3>

                        <dl class="ps-specs">
                            @foreach ($specs as $sp)
                                <div class="ps-spec">
                                    <span class="ps-spec-ic">
                                        @switch($sp['ic'])
                                            @case('brush')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.6">
                                                    <path d="M15 4 20 9 10 19H5v-5z" />
                                                    <path d="m13 6 5 5" />
                                                </svg>
                                            @break

                                            @case('ruler')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.6">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                                    <path d="M3 9h4M3 15h4M9 3v4M15 3v4" />
                                                </svg>
                                            @break

                                            @case('tag')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.6">
                                                    <path d="M3 12V4h8l9 9-8 8z" />
                                                    <circle cx="7.5" cy="7.5" r="1.2" />
                                                </svg>
                                            @break

                                            @case('drop')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.6">
                                                    <path d="M12 3s6 6.5 6 10a6 6 0 0 1-12 0c0-3.5 6-10 6-10z" />
                                                </svg>
                                            @break

                                            @case('shield')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.6">
                                                    <path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z" />
                                                    <path d="m9 12 2 2 4-4" />
                                                </svg>
                                            @break

                                            @case('box')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.6">
                                                    <path d="M3 7.5 12 3l9 4.5v9L12 21l-9-4.5z" />
                                                    <path d="M3 7.5 12 12l9-4.5M12 12v9" />
                                                </svg>
                                            @break

                                            @case('truck')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.6">
                                                    <path d="M3 7h11v9H3zM14 10h4l3 3v3h-7z" />
                                                    <circle cx="7" cy="18" r="1.6" />
                                                    <circle cx="17" cy="18" r="1.6" />
                                                </svg>
                                            @break

                                            @default
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.6">
                                                    <path d="M9 14 4 9l5-5" />
                                                    <path d="M4 9h11a5 5 0 0 1 0 10h-3" />
                                                </svg>
                                        @endswitch
                                    </span>
                                    <span class="ps-spec-txt">
                                        <dt>{{ $sp['label'] }}</dt>
                                        <dd>{{ $sp['value'] }}</dd>
                                    </span>
                                </div>
                            @endforeach
                        </dl>

                        <p class="ps-note">
                            Each piece is handmade — slight variations in colour, texture, and finish
                            are natural and what makes it unique.
                        </p>
                    </div>
                </section>

                {{-- ---------- 3. THE PROCESS ---------- --}}
                <section class="ps-block ps-process" id="product-shipping-tab">
                    <div class="ps-process-lhs">
                        <h3 class="ps-h3">The process</h3>
                        <p>Every painting goes through these six steps — no machines, no
                            shortcuts.</p>
                        <a href="{{ url('about') }}" class="ps-link">
                            Learn more about our process
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14m-6-6 6 6-6 6" />
                            </svg>
                        </a>
                    </div>

                    <ol class="ps-steps">
                        @foreach ($process as $i => $st)
                            <li class="ps-step">
                                <figure class="ps-step-img">
                                    <img src="{{ $shotAt($i) }}" alt="{{ $st[0] }}" loading="lazy"
                                        onerror="this.closest('.ps-step-img').classList.add('is-blank'); this.remove()">
                                    <span class="ps-step-n">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                </figure>
                                <b>{{ $st[0] }}</b>
                                <small>{{ $st[1] }}</small>
                            </li>
                        @endforeach
                    </ol>
                </section>

                {{-- ---------- 4. CARE ---------- --}}
                <section class="ps-block ps-care">
                    <h3 class="ps-h3">Care instructions</h3>
                    <ul class="ps-care-list">
                        @foreach ($care as $c)
                            <li>
                                <span class="ps-care-ic">
                                    @switch($c[0])
                                        @case('sun')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <circle cx="12" cy="12" r="4" />
                                                <path
                                                    d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4" />
                                            </svg>
                                        @break

                                        @case('cloth')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M4 6h16v6a8 8 0 0 1-16 0z" />
                                                <path d="M8 6V3h8v3" />
                                            </svg>
                                        @break

                                        @case('drop')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M12 3s6 6.5 6 10a6 6 0 0 1-12 0c0-3.5 6-10 6-10z" />
                                            </svg>
                                        @break

                                        @case('hand')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path
                                                    d="M9 11V5a1.5 1.5 0 0 1 3 0v6m0-1V4a1.5 1.5 0 0 1 3 0v7m0-2a1.5 1.5 0 0 1 3 0v6a6 6 0 0 1-6 6h-1a6 6 0 0 1-6-6v-3a1.5 1.5 0 0 1 3 0" />
                                            </svg>
                                        @break

                                        @default
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M3 7.5 12 3l9 4.5v9L12 21l-9-4.5z" />
                                                <path d="M3 7.5 12 12l9-4.5M12 12v9" />
                                            </svg>
                                    @endswitch
                                </span>
                                {{ $c[1] }}
                            </li>
                        @endforeach
                    </ul>
                </section>

                {{-- ---------- 5. REVIEWS (fully dynamic) ---------- --}}
                @php
                    // reviews already filtered (status = 1) in HomeController
                    $revs = $product->reviews;

                    // rating breakdown — from 5 down to 1
                    $revBreak = [];
                    for ($r = 5; $r >= 1; $r--) {
                        $c = $revs->where('rating', $r)->count();
                        $revBreak[$r] = [
                            'count' => $c,
                            'pct' => $reviewCount ? round(($c / $reviewCount) * 100) : 0,
                        ];
                    }

                    $verifiedCount = $revs->where('is_verified', 1)->count();
                    $revFirst = $revs->take(6); // showing the first 6
                    $revRest = $revs->slice(6); // the rest, shown on "load more"

                    // has the user already reviewed?
                    $myReview = auth()->check() ? $revs->firstWhere('user_id', auth()->id()) : null;
                @endphp

                <section class="ps-block ps-reviews" id="product-review-tab">
                    <div class="ps-rev-lhs">
                        <h3 class="ps-h3">Customer reviews</h3>

                        @if ($reviewCount)
                            <div class="ps-score-row">
                                <div class="ps-score">{{ number_format($avgRating, 1) }}</div>
                                <div>
                                    <span class="ratings"><span class="ratings-val"
                                            style="width: {{ $ratingPercent }}%"></span></span>
                                    <p class="ps-rev-count">
                                        {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}
                                        @if ($verifiedCount)
                                            · {{ $verifiedCount }} verified
                                        @endif
                                    </p>
                                </div>
                            </div>

                            {{-- breakdown bars — from real counts --}}
                            <ul class="ps-bars">
                                @foreach ($revBreak as $star => $b)
                                    <li>
                                        <span class="ps-bar-n">{{ $star }}★</span>
                                        <span class="ps-bar"><i style="width: {{ $b['pct'] }}%"></i></span>
                                        <span class="ps-bar-c">{{ $b['count'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="ps-rev-count">No reviews yet — yours could be the first.</p>
                        @endif

                        @auth
                            @if (!$myReview)
                                <button type="button" class="ps-writebtn" data-rev-open>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z" />
                                    </svg>
                                    Write a review
                                </button>
                            @else
                                <p class="ps-rev-mine">
                                    You have already reviewed this piece — thank you.
                                </p>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="ps-writebtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z" />
                                </svg>
                                Sign in to review
                            </a>
                        @endauth
                    </div>

                    <div class="ps-rev-rhs">

                        {{-- ---------- WRITE FORM (login users) ---------- --}}
                        @auth
                            @if (!$myReview)
                                <form class="ps-revform" id="psRevForm" method="POST"
                                    action="{{ route('product.review.store', $product->id) }}">
                                    @csrf
                                    <h4>Write your review</h4>

                                    <div class="ps-stars-pick" id="psStarPick">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <button type="button" data-star="{{ $i }}"
                                                aria-label="{{ $i }} star">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <path
                                                        d="m12 3 2.6 5.3 5.9.9-4.2 4.1 1 5.8L12 16.9l-5.3 2.2 1-5.8L3.5 9.2l5.9-.9z" />
                                                </svg>
                                            </button>
                                        @endfor
                                        <span class="ps-stars-out">Select Rating</span>
                                    </div>
                                    <input type="hidden" name="rating" id="psRating" value="">

                                    <textarea name="comment" rows="4" maxlength="1000"
                                        placeholder="How was the piece? Quality, colours, packaging — whatever you feel comfortable sharing."></textarea>

                                    @if ($errors->any())
                                        <p class="ps-formerr">{{ $errors->first() }}</p>
                                    @endif

                                    <div class="ps-revform-foot">
                                        <button type="submit" class="ps-revsubmit">Post review</button>
                                        <small>Your name will appear with the review.</small>
                                    </div>
                                </form>
                            @endif
                        @endauth

                        @if (session('review_ok'))
                            <p class="ps-formok">{{ session('review_ok') }}</p>
                        @endif

                        {{-- ---------- LIST ---------- --}}
                        <div class="ps-rev-list" id="psRevList">
                            @forelse($revFirst as $review)
                                @include('frontend.partials.review-card', ['review' => $review])
                            @empty
                                <div class="ps-rev-empty">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
                                        <path
                                            d="m12 3 2.6 5.3 5.9.9-4.2 4.1 1 5.8L12 16.9l-5.3 2.2 1-5.8L3.5 9.2l5.9-.9z" />
                                    </svg>
                                    <b>No reviews yet</b>
                                    <p>Please write your experience after buying this piece — it helps other buyers
                                        make their decision.</p>
                                </div>
                            @endforelse

                            @foreach ($revRest as $review)
                                <div class="ps-rev-more is-hidden">
                                    @include('frontend.partials.review-card', ['review' => $review])
                                </div>
                            @endforeach
                        </div>

                        @if ($revRest->count())
                            <button type="button" class="ps-loadmore" data-rev-more>
                                Show {{ $revRest->count() }} more {{ Str::plural('review', $revRest->count()) }}
                            </button>
                        @endif
                    </div>
                </section>
            </div>

            {{-- ===== RELATED (real products, Arthubly cards) ===== --}}
            @if (isset($relatedProducts) && $relatedProducts->count() > 0)
                <section class="ed ed-shelf" style="padding:64px 0 20px;background:none">
                    <div class="ed-head ed-head--center"><span class="eyebrow">More from this maker's world</span>
                        <h2 class="ed-title">You may also like</h2>
                    </div>
                    <div class="grid cols-4" data-reveal-group>
                        @foreach ($relatedProducts as $related)
                            @include('frontend.partials.arthubly-product-card', ['product' => $related])
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </section>

    {{-- ===== SEE IT ON YOUR WALL ===== --}}
    <div class="wv-overlay" id="wvOverlay" role="dialog" aria-modal="true" aria-labelledby="wvTitle">
        <div class="wv-shell">
            <button class="wv-close" type="button" data-wv-close aria-label="Close preview">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>

            <div class="wv-head">
                <span class="wv-eyebrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 21V8l9-5 9 5v13" />
                    </svg>
                    Preview at home
                </span>
                <h2 id="wvTitle">See it on your wall</h2>
                <p>Point your camera at the wall, or upload a photo. Then drag the piece to place it and drag the corner
                    to size it.</p>
            </div>

            <div class="wv-body">
                <div class="wv-stage" id="wvStage">
                    <img class="wv-room" id="wvRoom" alt="Your wall">
                    <video class="wv-live" id="wvLive" playsinline autoplay muted></video>
                    <span class="wv-badge-live"><i></i> Live</span>

                    <div class="wv-empty" id="wvEmpty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <rect x="3" y="4" width="18" height="16" rx="2" />
                            <path d="m3 16 5-4 4 3 3-2 6 5" />
                            <circle cx="8.5" cy="9" r="1.5" />
                        </svg>
                        <h3>Point your camera at the wall</h3>
                        <p>Hold your phone steady and step back — or upload a photo if you'd rather place it slowly.</p>
                        <div class="wv-empty-actions">
                            <button type="button" class="btn btn-primary" data-wv-cam>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 7h3l2-2h6l2 2h3v12H4z" />
                                    <circle cx="12" cy="13" r="3.5" />
                                </svg>
                                Open camera
                            </button>
                            <label class="btn btn-ghost" for="wvFile">Upload a photo</label>
                        </div>
                    </div>

                    <div class="wv-art" id="wvArt" data-src="{{ $mainImgPath }}">
                        <div class="wv-plate" id="wvPlate">
                            <span class="wv-shadow"></span>
                            <div class="wv-canvas f-gold has-frame" id="wvArtBox">
                                <div class="wv-mat on">
                                    <img id="wvArtImg" src="{{ $mainImgPath }}" alt="{{ $product->name }}">
                                </div>
                                <span class="wv-tone"></span>
                                <span class="wv-sheen"></span>
                                <span class="wv-grain"></span>
                            </div>
                        </div>
                        <span class="wv-grip is-turn" data-wv-grip="turn" title="Rotate">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 12a9 9 0 1 1-3-6.7" />
                                <path d="M21 4v5h-5" />
                            </svg>
                        </span>
                        <span class="wv-grip is-size" data-wv-grip="size" title="Resize">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="wv-controls">
                    <div class="wv-field">
                        <label for="wvSize">Size <b id="wvSizeOut">78 cm wide</b></label>
                        <input class="wv-range" type="range" id="wvSize" min="6" max="92"
                            step="0.5" value="26">
                    </div>

                    <div class="wv-field">
                        <label for="wvTurn">Tilt <b id="wvTurnOut">0°</b></label>
                        <input class="wv-range" type="range" id="wvTurn" min="-45" max="45"
                            step="1" value="0">
                    </div>

                    <div class="wv-field">
                        <label for="wvYaw">Wall angle <b id="wvYawOut">0°</b></label>
                        <input class="wv-range" type="range" id="wvYaw" min="-45" max="45"
                            step="1" value="0">
                    </div>

                    <div class="wv-field">
                        <label>Frame</label>
                        <div class="wv-frames">
                            <button type="button" data-wv-frame="gold" class="is-on"><i></i>Gold</button>
                            <button type="button" data-wv-frame="oak"><i></i>Wood</button>
                            <button type="button" data-wv-frame="black"><i></i>Black</button>
                            <button type="button" data-wv-frame="none"><i></i>None</button>
                        </div>
                    </div>

                    <label class="wv-toggle" for="wvMat">
                        <input type="checkbox" id="wvMat" checked> White mount board
                    </label>

                    <label class="wv-toggle" for="wvBlend">
                        <input type="checkbox" id="wvBlend" checked> Match the room's light
                    </label>

                    <div class="wv-error" id="wvError">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 8v5M12 16.5v.01" />
                        </svg>
                        <span id="wvErrorText"></span>
                    </div>

                    <p class="wv-note">Sizes are approximate — they assume about 3 m of wall in view. Check the listed
                        dimensions before you order.</p>

                    <div class="wv-buttons">
                        <button type="button" class="btn btn-brass" id="wvFreeze" hidden>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M4 7h3l2-2h6l2 2h3v12H4z" />
                                <circle cx="12" cy="13" r="3.5" />
                            </svg>
                            Freeze this frame
                        </button>
                        <button type="button" class="btn btn-ghost" data-wv-cam>Use my camera</button>
                        <label class="btn btn-ghost" for="wvFile"
                            style="width:100%;height:44px;font-size:13.5px">Upload a photo</label>
                        <button type="button" class="btn btn-ghost" id="wvReset">Reset placement</button>
                        <button type="button" class="btn btn-primary" id="wvSave" disabled>Save this preview</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="file" id="wvFile" accept="image/*" hidden>

    <div class="mzoom" id="mzoom" aria-hidden="true">
        <button type="button" class="mzoom-close" aria-label="Close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6 6 18M6 6l12 12" />
            </svg>
        </button>
        <div class="mzoom-stage" id="mzoomStage">
            <img id="mzoomImg" src="" alt="">
        </div>
        <div class="mzoom-hint" id="mzoomHint">Pinch to zoom · Double-tap to reset</div>
    </div>
@endsection

@push('scripts')
    {{-- =====================================================================
     PDP v2 — Size chips, Qty stepper, Color swatch
     (This block was missing — which is why +/- weren't working)
     ===================================================================== --}}
    <script>
        (function() {
            'use strict';

            /* ---------- QTY STEPPER (+ / −) ---------- */
            document.addEventListener('click', function(e) {
                var btn = e.target.closest('[data-qty-step]');
                if (!btn) return;

                e.preventDefault();

                var input = document.getElementById('qty');
                if (!input) return;

                var step = parseInt(btn.getAttribute('data-qty-step'), 10) || 1;
                var min = parseInt(input.getAttribute('min'), 10) || 1;
                var max = parseInt(input.getAttribute('max'), 10) || 999;
                var cur = parseInt(input.value, 10) || 1;

                input.value = Math.min(max, Math.max(min, cur + step));

                // notify old listeners (price update etc.) too
                input.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
                input.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
            });

            /* keep the limit enforced on manual typing too */
            document.addEventListener('change', function(e) {
                if (e.target.id !== 'qty') return;
                var min = parseInt(e.target.getAttribute('min'), 10) || 1;
                var max = parseInt(e.target.getAttribute('max'), 10) || 999;
                var v = parseInt(e.target.value, 10);
                if (isNaN(v)) v = min;
                e.target.value = Math.min(max, Math.max(min, v));
            });

            /* ---------- SIZE CHIPS → hidden <select#size> ---------- */
            document.addEventListener('click', function(e) {
                var chip = e.target.closest('.pdp-size-chip');
                if (!chip) return;

                e.preventDefault();

                // Out of stock chip — do nothing on click
                if (chip.hasAttribute('disabled') || chip.classList.contains('is-oos')) {
                    return;
                }

                var wrap = chip.closest('.pdp-size-chips');
                if (wrap) {
                    wrap.querySelectorAll('.pdp-size-chip').forEach(function(c) {
                        c.classList.remove('active');
                    });
                }
                chip.classList.add('active');

                var sel = document.getElementById('size');
                if (sel) {
                    sel.value = chip.getAttribute('data-size');
                    sel.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            });

            /* ---------- COLOR SWATCH ---------- */
            document.addEventListener('click', function(e) {
                var sw = e.target.closest('.pdp-swatches .color-swatch');
                if (!sw) return;

                e.preventDefault();

                var wrap = sw.closest('.pdp-swatches');
                if (wrap) {
                    wrap.querySelectorAll('.color-swatch').forEach(function(s) {
                        s.classList.remove('active');
                    });
                }
                sw.classList.add('active');

                var hidden = document.getElementById('selected-color');
                if (hidden) {
                    hidden.value = sw.getAttribute('data-color') || '';
                    hidden.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            });

            /* ---------- ✨ Click ripple — on both action buttons ---------- */
            document.addEventListener('click', function(e) {
                var btn = e.target.closest('#add-to-cart-btn, .btn-wishlist-pdp');
                if (!btn) return;

                var rect = btn.getBoundingClientRect();
                var size = Math.max(rect.width, rect.height);

                var ink = document.createElement('span');
                ink.className = 'pdp-ripple';
                ink.style.width = ink.style.height = size + 'px';
                ink.style.left = (e.clientX - rect.left - size / 2) + 'px';
                ink.style.top = (e.clientY - rect.top - size / 2) + 'px';

                btn.appendChild(ink);
                setTimeout(function() {
                    ink.remove();
                }, 620);
            });

            /* ---------- Desktop: once the sticky bar reaches its
                          original place, remove the shadow (flat look) ---------- */
            function initParkedObserver() {
                var bar = document.querySelector('.pdp-actions');
                if (!bar || !('IntersectionObserver' in window)) return;

                // sentinel — right below the bar. Becoming visible means
                // the bar has returned to its original place (no longer sticky floating).
                var mark = document.createElement('div');
                mark.style.height = '1px';
                mark.setAttribute('aria-hidden', 'true');
                bar.parentNode.insertBefore(mark, bar.nextSibling);

                new IntersectionObserver(function(entries) {
                    bar.classList.toggle('is-parked', entries[0].isIntersecting);
                }, {
                    rootMargin: '0px 0px -40px 0px'
                }).observe(mark);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initParkedObserver);
            } else {
                initParkedObserver();
            }

        })();
    </script>

    <script>
        (function() {
            var isTouch = window.matchMedia('(hover: none), (max-width: 1080px)').matches;

            var box = document.getElementById('mzoom');
            var stage = document.getElementById('mzoomStage');
            var img = document.getElementById('mzoomImg');
            if (!box) return;

            var scale = 1,
                lastScale = 1,
                tx = 0,
                ty = 0,
                startX = 0,
                startY = 0;
            var startDist = 0,
                lastTap = 0,
                dragging = false;

            function apply() {
                img.style.transform = 'translate(' + tx + 'px,' + ty + 'px) scale(' + scale + ')';
                box.classList.toggle('zoomed', scale > 1.05);
            }

            function reset() {
                scale = lastScale = 1;
                tx = ty = 0;
                apply();
            }

            function closeBox() {
                box.classList.remove('open');
                document.body.classList.remove('mzoom-lock');
            }

            // ---------- open the lightbox ----------
            window.openMzoom = function(el) {
                if (!el) el = document.getElementById('product-zoom');
                if (!el) return;

                var src = el.getAttribute('data-zoom-image') || el.currentSrc || el.src;

                // if <picture> exists, take the largest srcset entry
                var pic = el.closest ? el.closest('picture') : null;
                var ss = pic ? pic.querySelector('source') : null;
                if (ss && ss.srcset) {
                    var parts = ss.srcset.split(',');
                    src = parts[parts.length - 1].trim().split(' ')[0];
                }

                img.src = src;
                img.alt = el.alt || '';
                reset();
                box.classList.add('open');
                document.body.classList.add('mzoom-lock');

                var hint = document.getElementById('mzoomHint');
                if (hint) {
                    hint.textContent = isTouch ?
                        'Pinch to zoom · Double-tap to reset' :
                        'Click image to zoom · Esc to close';
                }
            };

            // ---------- Zoom button (both desktop + mobile) ----------
            document.addEventListener('click', function(e) {
                var btn = e.target.closest('#btn-product-gallery');
                if (!btn) return;
                e.preventDefault();
                openMzoom(document.getElementById('product-zoom'));
            });

            // ---------- Mobile: also open on tapping the image ----------
            if (isTouch) {
                document.addEventListener('click', function(e) {
                    var t = e.target.closest('#product-zoom, .zoom-trigger');
                    if (!t) return;
                    e.preventDefault();
                    openMzoom(t);
                });
            }

            // ---------- Show the hint the first time ----------
            if (isTouch) {
                var pHint = document.getElementById('pdpZoomHint');
                var seen = false;
                try {
                    seen = !!sessionStorage.getItem('pdpZoomHintSeen');
                } catch (err) {}

                if (pHint && !seen) {
                    setTimeout(function() {
                        pHint.classList.add('show');
                        setTimeout(function() {
                            pHint.classList.add('fade');
                            setTimeout(function() {
                                pHint.classList.remove('show', 'fade');
                            }, 600);
                        }, 3500);
                    }, 900);
                    try {
                        sessionStorage.setItem('pdpZoomHintSeen', '1');
                    } catch (err) {}
                }
            }

            // ---------- close ----------
            box.querySelector('.mzoom-close').addEventListener('click', closeBox);

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && box.classList.contains('open')) closeBox();
            });

            // ---------- desktop: zoom in/out on click ----------
            stage.addEventListener('click', function(e) {
                if (e.target !== img) {
                    closeBox();
                    return;
                }
                scale = scale > 1.05 ? 1 : 2.2;
                lastScale = scale;
                tx = ty = 0;
                apply();
            });

            // ---------- touch gestures ----------
            function dist(t) {
                var dx = t[0].clientX - t[1].clientX,
                    dy = t[0].clientY - t[1].clientY;
                return Math.sqrt(dx * dx + dy * dy);
            }

            stage.addEventListener('touchstart', function(e) {
                if (e.touches.length === 2) {
                    startDist = dist(e.touches);
                    dragging = false;
                } else if (e.touches.length === 1) {
                    var now = Date.now();
                    if (now - lastTap < 300) {
                        scale = scale > 1.05 ? 1 : 2.5;
                        lastScale = scale;
                        tx = ty = 0;
                        apply();
                    }
                    lastTap = now;

                    dragging = scale > 1.05;
                    startX = e.touches[0].clientX - tx;
                    startY = e.touches[0].clientY - ty;
                }
            }, {
                passive: true
            });

            stage.addEventListener('touchmove', function(e) {
                if (e.touches.length === 2) {
                    e.preventDefault();
                    scale = Math.min(4, Math.max(1, lastScale * (dist(e.touches) / startDist)));
                    apply();
                } else if (dragging && e.touches.length === 1 && scale > 1.05) {
                    e.preventDefault();
                    tx = e.touches[0].clientX - startX;
                    ty = e.touches[0].clientY - startY;
                    apply();
                }
            }, {
                passive: false
            });

            stage.addEventListener('touchend', function(e) {
                lastScale = scale;
                if (scale <= 1.02) reset();
                if (e.touches.length === 0) dragging = false;
            }, {
                passive: true
            });
        })();
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let checkJq = setInterval(function() {
                if (typeof jQuery !== 'undefined') {
                    clearInterval(checkJq);
                    let $ = jQuery;

                    let variations = @json($product->variations);
                    let galleryImages = @json($product->images);
                    let allSizes = @json($uniqueSizes ?? []);
                    let assetUrl = "{{ asset('public/uploads/products') }}";

                    // ---- Share button ----
                    $(document).on('click', '.btn-share-icon', function(e) {
                        e.preventDefault();

                        let shareData = {
                            title: document.title,
                            text: "{{ addslashes($product->name) }}",
                            url: "{{ url()->current() }}"
                        };

                        if (navigator.share) {
                            navigator.share(shareData).catch(function() {
                                // user cancelled share — do nothing
                            });
                        } else {
                            navigator.clipboard.writeText(shareData.url).then(function() {
                                let toastHtml = `<div class="toast show" role="alert">
                                    <div class="t-check">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                                            <path d="M20 6 9 17l-5-5" />
                                        </svg>
                                    </div>
                                    <div class="t-body"><b>Link copied to clipboard!</b></div>
                                    <button type="button" class="close">&times;</button>
                                </div>`;
                                $('#toastWrap').append(toastHtml);
                                setTimeout(function() {
                                    $('#toastWrap .toast').first().removeClass(
                                        'show');
                                    setTimeout(function() {
                                        $('#toastWrap .toast').first()
                                            .remove();
                                    }, 400);
                                }, 3000);
                            }).catch(function() {
                                window.prompt("Copy this link:", shareData.url);
                            });
                        }
                    });

                    $(document).on('click', '#toastWrap .toast .close', function() {
                        $(this).closest('.toast').remove();
                    });
                    // =========================================================
                    // DESKTOP HOVER ZOOM (native — elevateZoom hata diya)
                    //
                    // elevateZoom caches image offset during init
                    // Your header is sticky and collapses on scroll
                    // — as layout changes, the cached offset becomes incorrect,
                    // which is why lens was floating outside image.
                    //
                    // This version takes live position on every mousemove,
                    // so it never goes off. No overlay is created either,
                    // so it does not block arrow/button clicks.
                    // =========================================================
                    const PDP_ZOOM = 2.4;

                    function initZoom() {
                        // clean up any leftover elevateZoom instance
                        $('.zoomContainer, .zoomWindowContainer, .zoomLens').remove();
                        $('#product-zoom').removeData('elevateZoom').removeData('zoomImage').removeAttr(
                            'style');
                    }

                    function pdpZoomOn(e) {
                        if (window.innerWidth < 1081) return; // mobile has lightbox
                        if ($('#mzoom').hasClass('open')) return;

                        const $fig = $('#product-zoom').closest('.pdp-main');
                        const $img = $('#product-zoom');
                        if (!$fig.length || !$img.length) return;

                        // LIVE rect — fresh on every move, so scroll/resize does not affect it
                        const r = $fig[0].getBoundingClientRect();
                        let x = ((e.clientX - r.left) / r.width) * 100;
                        let y = ((e.clientY - r.top) / r.height) * 100;

                        x = Math.max(0, Math.min(100, x));
                        y = Math.max(0, Math.min(100, y));

                        $img.css({
                            'transform-origin': x + '% ' + y + '%',
                            'transform': 'scale(' + PDP_ZOOM + ')'
                        });
                        $fig.addClass('is-zooming');
                    }

                    function pdpZoomOff() {
                        $('#product-zoom').css({
                            'transform': 'none'
                        });
                        $('#product-zoom').closest('.pdp-main').removeClass('is-zooming');
                    }

                    $(document)
                        .on('mousemove', '.pdp-main', pdpZoomOn)
                        .on('mouseleave', '.pdp-main', pdpZoomOff);

                    // zoom disabled when hovering arrows/zoom button — otherwise clicking is difficult
                    $(document).on('mouseenter', '.pdp-nav, #btn-product-gallery', pdpZoomOff);
                    $(document).on('mousemove', '.pdp-nav, #btn-product-gallery', function(e) {
                        e.stopPropagation();
                    });

                    // reset once on page load
                    setTimeout(initZoom, 300);

                    $(window).on('resize', function() {
                        pdpZoomOff();
                    });

                    // ================= COLOR CLICK =================
                    $('.color-swatch').on('click', function(e) {
                        e.preventDefault();

                        $('.color-swatch').removeClass('active');
                        $(this).addClass('active');

                        // ⬇️ Put the selected color into the hidden input (in its ORIGINAL case, so it matches the DB)
                        $('#selected-color').val($(this).data('color'));

                        let selectedColor = String($(this).data('color')).toLowerCase();

                        // 1. MAIN IMAGE CHANGE
                        let variationImgObj = variations.find(v =>
                            v.color && String(v.color).toLowerCase() === selectedColor && v
                            .image
                        );

                        let mainImageUrl = variationImgObj ?
                            (assetUrl + '/variations/' + variationImgObj.image) :
                            "{{ asset('public/uploads/products/no-image.jpg') }}";

                        // Change the image src manually
                        $('#product-zoom').attr('src', mainImageUrl).attr('data-zoom-image',
                            mainImageUrl);

                        // 2. GALLERY FILTER (Filter just the selected color)
                        let galleryHtml = '';

                        // First image is Variation's thumbnail
                        if (variationImgObj) {
                            let vParts = variationImgObj.image.split('.');
                            let vThumb = vParts[0] + '_side.' + vParts[1];
                            galleryHtml += `
                                <a class="product-gallery-item active" href="#" data-image="${mainImageUrl}" data-zoom-image="${mainImageUrl}">
                                    <img src="${assetUrl}/variations/side/${vThumb}">
                                </a>
                            `;
                        }

                        // Remaining images, from the gallery folder
                        let filteredGallery = galleryImages.filter(img =>
                            img.product_color && String(img.product_color).toLowerCase() ===
                            selectedColor
                        );

                        // If there are no extra gallery images for that color, only then show default images
                        if (filteredGallery.length === 0) {
                            filteredGallery = galleryImages.filter(img => !img.product_color || img
                                .product_color === "");
                        }

                        filteredGallery.forEach(function(img) {
                            let fullImg = assetUrl + '/gallery/' + img.image;
                            let thumbImg = img.side_image ?
                                assetUrl + '/gallery/side/' + img.side_image :
                                assetUrl + '/gallery/' + img.image;

                            galleryHtml += `
                                <a class="product-gallery-item" href="#" data-image="${fullImg}" data-zoom-image="${fullImg}">
                                    <img src="${thumbImg}">
                                </a>
                            `;
                        });

                        // Emptying the old gallery and inserting new data
                        $('#product-zoom-gallery').empty().append(galleryHtml);

                        // Show/hide arrows based on the new image count
                        if (typeof pdpToggleNav === 'function') pdpToggleNav();


                        // 3. SIZE & PRICE LOGIC
                        updateSizeAndPrice(selectedColor);

                        // 4. RE-INITIALIZE ZOOM plugin after HTML changes
                        setTimeout(initZoom, 100);
                    });

                    function updateSizeAndPrice(selectedColor) {
                        // Size dropdown and visual chips update logic
                        if (allSizes.length > 0) {
                            let sizeHtml = '<option value="">Select a size</option>';
                            let firstAvailableSize = null;

                            allSizes.forEach(function(sz) {
                                let matchVar = variations.find(v =>
                                    v.color && v.size &&
                                    String(v.color).toLowerCase() === selectedColor &&
                                    String(v.size).toLowerCase() === String(sz).toLowerCase()
                                );

                                let $chip = $('.pdp-size-chip[data-size="' + sz + '"]');

                                // Check stock (will check both stock or quantity fields)
                                if (matchVar && (parseInt(matchVar.stock) > 0 || parseInt(matchVar
                                        .quantity) > 0)) {
                                    sizeHtml +=
                                        `<option value="${sz}" data-price="${matchVar.price}">${String(sz).toUpperCase()}</option>`;

                                    // Enable the chip
                                    $chip.removeAttr('disabled').removeClass('pc-off').attr('title',
                                        '');
                                    if (!firstAvailableSize) firstAvailableSize = sz;
                                } else {
                                    sizeHtml +=
                                        `<option value="${sz}" disabled>${String(sz).toUpperCase()} - Out of Stock</option>`;

                                    // Disable the chip
                                    $chip.attr('disabled', 'disabled').addClass('pc-off')
                                        .removeClass('active').attr('title', 'Out of Stock');
                                }
                            });
                            $('#size').html(sizeHtml);

                            // If currently selected size goes out of stock, auto-select first available
                            let activeSize = $('.pdp-size-chip.active').data('size');
                            let isNowDisabled = $('.pdp-size-chip[data-size="' + activeSize + '"]').attr(
                                'disabled');

                            if ((!activeSize || isNowDisabled) && firstAvailableSize) {
                                $('.pdp-size-chip').removeClass('active');
                                $('.pdp-size-chip[data-size="' + firstAvailableSize + '"]').addClass(
                                    'active');
                                $('#size').val(firstAvailableSize).trigger('change');
                            }
                        }

                        // Default Price update
                        let firstAvailableVar = variations.find(v => v.color && String(v.color)
                            .toLowerCase() === selectedColor);
                        if (firstAvailableVar) {
                            let formattedPrice = parseFloat(firstAvailableVar.price).toFixed(2);
                            $('#display-price').text('₹' + formattedPrice);
                        }
                    }

                    // When User changes "Size" from dropdown, show accurate price
                    $(document).on('change', '#size', function() {
                        let selectedOption = $(this).find('option:selected');
                        let price = selectedOption.data('price');

                        if (price) {
                            let formattedPrice = parseFloat(price).toFixed(2);
                            $('#display-price').text('₹' + formattedPrice);
                        }
                    });

                    // Gallery Item Click Handler
                    $(document).on('click', '.product-gallery-item', function(e) {
                        e.preventDefault();
                        $('.product-gallery-item').removeClass('active');
                        $(this).addClass('active');

                        let image = $(this).data('image');
                        let zoomImage = $(this).data('zoom-image');

                        $('#product-zoom').attr('src', image).attr('data-zoom-image', zoomImage);

                        // Re-initialize plugin after swapping
                        initZoom();
                    });

                    // ===== Main image prev / next arrows =====
                    function pdpSlide(dir) {
                        let $items = $('#product-zoom-gallery .product-gallery-item');

                        // Fallback: if there are no thumbs, slide via the color swatches
                        if ($items.length < 2) {
                            let $sw = $('.color-swatch');
                            if ($sw.length > 1) {
                                let si = $sw.index($sw.filter('.active'));
                                if (si < 0) si = 0;
                                si = (si + dir + $sw.length) % $sw.length;
                                $sw.eq(si).trigger('click');
                            }
                            return;
                        }

                        let i = $items.index($items.filter('.active'));
                        if (i < 0) i = 0;
                        i = (i + dir + $items.length) % $items.length;

                        $items.eq(i).trigger('click');

                        // Bring active thumb into view
                        let el = $items.get(i);
                        if (el && el.scrollIntoView) {
                            el.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest',
                                inline: 'nearest'
                            });
                        }
                    }

                    function pdpToggleNav() {
                        let count = $('#product-zoom-gallery .product-gallery-item').length;
                        $('.pdp-nav').toggle(count > 1);
                    }

                    $(document).on('click', '#pdp-prev', function(e) {
                        e.preventDefault();
                        pdpSlide(-1);
                    });

                    $(document).on('click', '#pdp-next', function(e) {
                        e.preventDefault();
                        pdpSlide(1);
                    });

                    // Keyboard arrows (only when lightbox is closed)
                    $(document).on('keydown', function(e) {
                        if ($('#mzoom').hasClass('open')) return;
                        if ($(e.target).is('input, textarea, select')) return;
                        if (e.key === 'ArrowLeft') pdpSlide(-1);
                        if (e.key === 'ArrowRight') pdpSlide(1);
                    });

                    // Initial Trigger (Check URL parameter)
                    setTimeout(() => {
                        const urlParams = new URLSearchParams(window.location.search);
                        const preSelectedColor = urlParams.get('color');

                        if (preSelectedColor) {
                            let targetSwatch = $(`.color-swatch[title='${preSelectedColor}']`);
                            if (targetSwatch.length > 0) {
                                targetSwatch.trigger('click');
                            } else {
                                $('.color-swatch').first().trigger('click');
                            }
                        } else {
                            if ($('.color-swatch').length > 0) {
                                $('.color-swatch').first().trigger('click');
                            }
                        }
                    }, 300);
                }
            }, 100);
        });
    </script>
    {{-- ===== REVIEWS — star picker, form toggle, load more ===== --}}
    <script>
        (function() {
            var form = document.getElementById('psRevForm');

            /* ---- "Write a review" → open form and scroll there ---- */
            document.querySelectorAll('[data-rev-open]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    if (!form) return;
                    form.classList.add('is-open');
                    form.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    var ta = form.querySelector('textarea');
                    if (ta) setTimeout(function() {
                        ta.focus();
                    }, 400);
                });
            });

            /* ---- star picker ---- */
            var pick = document.getElementById('psStarPick');
            if (pick) {
                var stars = Array.prototype.slice.call(pick.querySelectorAll('button'));
                var input = document.getElementById('psRating');
                var out = pick.querySelector('.ps-stars-out');
                var labels = ['', 'Not good', 'Okay', 'Good', 'Very good', 'Excellent'];
                var chosen = 0;

                function paint(n) {
                    stars.forEach(function(s, i) {
                        s.classList.toggle('on', i < n);
                    });
                    if (out) out.textContent = n ? labels[n] : 'Rating chunein';
                }

                stars.forEach(function(s, i) {
                    s.addEventListener('mouseenter', function() {
                        paint(i + 1);
                    });
                    s.addEventListener('click', function() {
                        chosen = i + 1;
                        if (input) input.value = chosen;
                        paint(chosen);
                    });
                });

                pick.addEventListener('mouseleave', function() {
                    paint(chosen);
                });
            }

            /* ---- don't allow submit without a rating ---- */
            if (form) {
                form.addEventListener('submit', function(e) {
                    var val = document.getElementById('psRating');
                    if (!val || !val.value) {
                        e.preventDefault();
                        if (window.abToast) window.abToast('Please select a rating first.', 'error');
                        if (pick) pick.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                });
            }

            /* ---- show the remaining reviews ---- */
            var more = document.querySelector('[data-rev-more]');
            if (more) {
                more.addEventListener('click', function() {
                    document.querySelectorAll('.ps-rev-more').forEach(function(el) {
                        el.classList.remove('is-hidden');
                    });
                    more.remove();
                });
            }

            /* ---- straight to reviews after submit + keep form open ---- */
            if (location.hash === '#product-review-tab') {
                var sec = document.getElementById('product-review-tab');
                if (sec) setTimeout(function() {
                    sec.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 120);
            }
            @if ($errors->any())
                if (form) form.classList.add('is-open');
            @endif
        })();
    </script>

    {{-- ===== PRODUCT STORY — section nav scrollspy (replacing old tab JS) ===== --}}
    <script>
        (function() {
            var nav = document.querySelector('.ps-nav');
            if (!nav) return;

            var links = Array.prototype.slice.call(nav.querySelectorAll('a'));
            var targets = links.map(function(a) {
                return document.querySelector(a.getAttribute('href'));
            });

            // smooth scroll — so it doesn't hide under sticky nav
            links.forEach(function(a, i) {
                a.addEventListener('click', function(e) {
                    if (!targets[i]) return;
                    e.preventDefault();
                    targets[i].scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    history.replaceState(null, '', a.getAttribute('href'));
                });
            });

            if (!('IntersectionObserver' in window)) return;

            var io = new IntersectionObserver(function(entries) {
                entries.forEach(function(en) {
                    if (!en.isIntersecting) return;
                    var i = targets.indexOf(en.target);
                    if (i < 0) return;
                    links.forEach(function(l) {
                        l.classList.remove('is-on');
                    });
                    links[i].classList.add('is-on');
                });
            }, {
                rootMargin: '-25% 0px -60% 0px'
            });

            targets.forEach(function(t) {
                if (t) io.observe(t);
            });
        })();
    </script>
@endpush
