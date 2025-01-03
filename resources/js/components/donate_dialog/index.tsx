import React from "react";

import Amount from "./amount";
import Button from "./button";

export default function DonateDialog() {
    return (
        <div className="fixed z-10 justify-center w-full md:w-[460px] h-20 transform -translate-x-1/2 bg-white border rounded-t-none md:rounded-t-lg rounded-lg shadow-2xl border-neutral-light left-1/2 bottom-0 md:bottom-10">
            <form className="grid grid-cols-[0.7fr_0.3fr] h-full w-full place-content-center">
                <Amount />
                <Button />
            </form>
        </div>
    );
}
