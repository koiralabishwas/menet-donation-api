import { ReactNode } from "react";

import { Link } from "@inertiajs/react";

type Props = {
    title: string;
    children: ReactNode;
};

export default function Card({ title, children }: Props) {
    return (
        <Link href={`/projects/${title}`}>
            <div className="bg-white border rounded-lg shadow-lg cursor-pointer border-neutral-light xl:transition-transform xl:duration-500 xl:hover:scale-105">
                <img
                    src="/images/altervoice.jpg"
                    alt="Logo"
                    className="w-full rounded-lg rounded-b-none"
                />
                <article className="p-2">
                    <h1 className="text-3xl font-bold text-primary">{title}</h1>
                    <div className="leading-8 line-clamp-3">{children}</div>
                </article>
            </div>
        </Link>
    );
}
